<?php

namespace Tests\Feature;

use App\Models\CompanyInfo;
use App\Models\ContactMessage;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Site public ASENIM et sa liaison avec l'administration.
 */
class FrontTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'phone' => '0600000001']);
    }

    // ------------------------------------------------------------ Pages

    public function test_all_public_pages_render_for_guests_without_broken_links(): void
    {
        foreach (['/', '/qui-sommes-nous', '/bureau-executif', '/actualites', '/galerie', '/icmic', '/contact', '/inscription'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertStringNotContainsString('.html"', $html, "Lien .html restant sur $url");
            $this->assertDoesNotMatchRegularExpression('/(src|href)="assets\//', $html, "Chemin relatif restant sur $url");
            $this->assertStringContainsString('/front/assets/css/main.css', $html);
        }

        $this->get('/espace-membre')->assertRedirect('/index');
    }

    public function test_company_details_from_admin_appear_on_the_public_site(): void
    {
        // Valeurs par défaut tant que l'admin n'a rien saisi
        $this->get('/')->assertSee('contact@asenim.org');

        CompanyInfo::create(['address' => "1 rue Test\n5000 Monastir", 'phone' => '+216 70 000 000', 'email' => 'bureau@asenim.tn']);

        foreach (['/', '/contact', '/actualites'] as $url) {
            $this->get($url)->assertSee('bureau@asenim.tn')->assertSee('+216 70 000 000')
                ->assertSee('href="tel:+21670000000"', false);
        }
        $this->get('/contact')->assertSee('1 rue Test, 5000 Monastir');

        // Page contact : coordonnées dans le bloc principal ET dans le pied de page
        $html = $this->get('/contact')->getContent();
        $this->assertStringContainsString('asenim-footer__contact', $html);
        $this->assertGreaterThanOrEqual(2, substr_count($html, 'bureau@asenim.tn') / 2);
    }

    public function test_header_reflects_authentication_state(): void
    {
        $this->get('/')->assertSee('aria-controls="asenimAuthModal"', false)->assertSee('authenticated: false', false);

        $member = User::factory()->create(['phone' => '0711111111']);
        $this->actingAs($member)->get('/')->assertSee('user-btn--auth', false)->assertSee(route('index'));
    }

    // ------------------------------------------------------------ Actualités

    public function test_news_published_in_admin_appear_on_the_public_site(): void
    {
        $admin = $this->admin();
        $news = News::create(['title' => 'Assemblée générale 2026', 'events_news' => 'event', 'content' => "Ordre du jour\nSuite du texte", 'date' => now()->addWeek(), 'user_id' => $admin->id]);
        $archived = News::create(['title' => 'Ancienne annonce archivée', 'events_news' => 'news', 'content' => 'x', 'date' => now(), 'user_id' => $admin->id]);
        $archived->delete();

        $this->get('/')->assertSee('Assemblée générale 2026')->assertSee(route('front.news.show', $news->id))->assertDontSee('Ancienne annonce archivée');
        $this->get('/actualites')->assertSee('Assemblée générale 2026')->assertSee('ÉVÉNEMENT')->assertDontSee('Ancienne annonce archivée');

        $this->get(route('front.news.show', $news->id))->assertOk()
            ->assertSee('Assemblée générale 2026')
            // Ancien texte brut : un paragraphe par ligne
            ->assertSee('<div class="news-detail-content"><p>Ordre du jour</p><p>Suite du texte</p></div>', false);

        $this->get(route('front.news.show', $archived->id))->assertNotFound();
    }

    public function test_news_list_is_paginated_and_searchable(): void
    {
        $admin = $this->admin();
        for ($i = 1; $i <= 11; $i++) {
            News::create(['title' => "Actu numéro $i", 'events_news' => 'news', 'content' => 'contenu', 'date' => now()->subDays($i), 'user_id' => $admin->id]);
        }

        $page1 = $this->get('/actualites')->assertOk()->getContent();
        $this->assertSame(9, substr_count($page1, 'class="actualite-card"'));
        $this->assertStringContainsString('À la une', $page1);
        $this->assertStringContainsString('page=2', $page1);

        $page2 = $this->get('/actualites?page=2')->getContent();
        $this->assertSame(2, substr_count($page2, 'class="actualite-card"'));

        $this->get('/actualites?q=numéro+7')->assertSee('1 résultat')->assertSee('Actu numéro 7')->assertDontSee('Actu numéro 8');
        $this->get('/actualites?q=introuvable')->assertSee('Aucune actualité trouvée');
    }

    // ------------------------------------------------------------ Contact

    public function test_contact_form_stores_message_for_admins(): void
    {
        $this->postJson('/contact', ['name' => 'Sami', 'email' => 'sami@example.com', 'subject' => 'Partenariat', 'message' => 'Bonjour'])
            ->assertOk()->assertJsonFragment(['message' => 'Merci, votre message a bien été envoyé. L’équipe ASENIM vous répondra dans les meilleurs délais.']);

        $this->assertDatabaseHas('contact_messages', ['email' => 'sami@example.com', 'subject' => 'Partenariat', 'read_at' => null]);

        $this->postJson('/contact', ['name' => '', 'email' => 'pas-un-mail'])
            ->assertStatus(422)->assertJsonFragment(['Le champ adresse e-mail doit être une adresse e-mail valide.']);

        // Robot : champ piège rempli → rien n'est enregistré
        $this->postJson('/contact', ['name' => 'Bot', 'email' => 'bot@x.io', 'subject' => 's', 'message' => 'spam', 'website' => 'http://spam'])->assertOk();
        $this->assertDatabaseCount('contact_messages', 1);
    }

    public function test_admin_reads_and_manages_contact_messages(): void
    {
        $admin = $this->admin();
        $message = ContactMessage::create(['name' => 'Sami', 'email' => 'sami@example.com', 'subject' => 'Partenariat', 'message' => 'Bonjour <b>équipe</b>']);

        $this->actingAs($admin)->get('/index')->assertSee('message(s) non lu(s)');
        $this->actingAs($admin)->get(route('messages.index'))->assertOk()->assertSee('Partenariat')->assertSee('Nouveau');
        $this->actingAs($admin)->get(route('messages.show', $message->id))->assertOk()
            ->assertSee('Bonjour &lt;b&gt;équipe&lt;/b&gt;', false);
        $this->assertNotNull($message->fresh()->read_at);

        $this->actingAs($admin)->get(route('messages.index', ['filter' => 'unread']))->assertSee('Tout est lu');
        $this->actingAs($admin)->post(route('messages.unread', $message->id))->assertRedirect(route('messages.index'));
        $this->assertNull($message->fresh()->read_at);

        $this->actingAs($admin)->delete(route('messages.destroy', $message->id))->assertSessionHas('success');
        $this->assertDatabaseCount('contact_messages', 0);

        $member = User::factory()->create(['phone' => '0722222222']);
        $this->actingAs($member)->get(route('messages.index'))->assertRedirect(route('index'));
    }

    // ------------------------------------------------------------ Inscription & connexion

    public function test_registration_creates_pending_account_that_admin_must_approve(): void
    {
        $this->postJson('/inscription', [
            'name' => 'Leila Ben Salah', 'email' => 'leila@example.com',
            'password' => 'motdepasse1', 'password_confirmation' => 'motdepasse1', 'terms' => 'on',
        ])->assertCreated()->assertJsonFragment(['message' => 'Votre demande d’inscription a bien été envoyée. Un administrateur doit la valider : vous pourrez ensuite vous connecter avec votre adresse e-mail et votre mot de passe.']);

        $user = User::where('email', 'leila@example.com')->firstOrFail();
        $this->assertSame('LEILA', $user->first_name);
        $this->assertSame('Ben salah', $user->last_name);
        $this->assertNull($user->approved_at);
        $this->assertSame('membre', $user->role);

        // Doublon d'e-mail
        $this->postJson('/inscription', ['name' => 'X Y', 'email' => 'leila@example.com', 'password' => 'motdepasse1', 'password_confirmation' => 'motdepasse1'])
            ->assertStatus(422)->assertJsonValidationErrors('email');

        // Connexion refusée tant que le compte n'est pas validé
        $this->postJson('/login', ['email' => 'leila@example.com', 'password' => 'motdepasse1'])
            ->assertStatus(422)->assertJsonFragment(['message' => 'Votre inscription est en attente de validation par un administrateur. Vous recevrez l’accès dès qu’elle sera acceptée.']);
        $this->assertGuest();

        // Le compte n'apparaît pas parmi les actifs, mais dans « En attente »
        $admin = $this->admin();
        $this->actingAs($admin)->get('/list-users')->assertDontSee('leila@example.com');
        $this->actingAs($admin)->get(route('users.pending'))->assertOk()->assertSee('leila@example.com');
        $this->actingAs($admin)->get('/index')->assertSee('inscription(s) en attente');

        $this->actingAs($admin)->post(route('users.approve', $user->id))->assertSessionHas('success');
        $this->assertNotNull($user->fresh()->approved_at);
        auth()->logout();

        // Après validation : connexion depuis la modale → étape « compléter le profil »
        $this->postJson('/login', ['email' => 'leila@example.com', 'password' => 'motdepasse1'])
            ->assertOk()->assertJson(['redirect' => route('user.edit.personnel.membre.first')]);
        $this->assertAuthenticatedAs($user->fresh());
    }

    public function test_admin_can_reject_a_registration(): void
    {
        $pending = User::factory()->create(['email' => 'spam@example.com', 'approved_at' => null, 'phone' => null]);
        $admin = $this->admin();

        $this->actingAs($admin)->delete(route('users.reject', $pending->id))->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['email' => 'spam@example.com']);

        // Un compte déjà validé ne peut pas être « refusé »
        $this->actingAs($admin)->delete(route('users.reject', $admin->id))->assertNotFound();
    }

    public function test_login_modal_reports_errors_in_json(): void
    {
        $user = User::factory()->create(['phone' => '0733333333', 'role' => 'admin']);

        $this->postJson('/login', ['email' => $user->email, 'password' => 'mauvais'])
            ->assertStatus(422)->assertJsonFragment(['message' => 'Les identifiants fournis sont incorrects.']);
        $this->postJson('/login', ['email' => '', 'password' => ''])
            ->assertStatus(422)->assertJsonValidationErrors(['email', 'password']);

        $this->postJson('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertOk()->assertJson(['redirect' => route('index')]);
    }

    public function test_rich_text_from_admin_toolbar_is_sanitized_and_rendered(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('news.store'), [
            'title' => 'Article mis en forme', 'events_news' => 'news', 'date' => '2026-10-01T10:00',
            'content' => '<h2>Programme</h2><p>Une <strong>journée</strong> <em>riche</em></p><ul><li>Atelier</li></ul><script>alert(1)</script><p onmouseover="x()">Fin</p>',
        ])->assertRedirect();

        $news = News::where('title', 'Article mis en forme')->firstOrFail();
        $this->assertSame('<h2>Programme</h2><p>Une <strong>journée</strong> <em>riche</em></p><ul><li>Atelier</li></ul><p>Fin</p>', $news->content);

        // Page publique : HTML rendu ; extraits et description en texte brut
        $this->get(route('front.news.show', $news->id))->assertOk()
            ->assertSee('<h2>Programme</h2>', false)->assertSee('<strong>journée</strong>', false)
            ->assertSee('content="Programme Une journée riche Atelier Fin"', false)
            ->assertDontSee('alert(1)', false);
        $this->get('/actualites')->assertSee('Programme Une journée riche Atelier Fin')->assertDontSee('&lt;h2&gt;', false);

        // Admin : extrait en texte, modale de lecture en HTML
        $this->actingAs($admin)->get('/news')->assertSee('Programme Une journée riche Atelier Fin')
            ->assertSee('<div class="article-body rich-content"><h2>Programme</h2>', false);
    }

    public function test_biography_and_activities_use_the_toolbar_and_are_sanitized(): void
    {
        $member = User::factory()->create(['phone' => '0744444444']);

        $this->actingAs($member)->get(route('users.connected.edit.biography'))->assertOk()
            ->assertSee('data-rich-text', false)->assertSee('quill@2.0.3', false)->assertSee('assets/js/rich-text.js', false);

        $this->actingAs($member)->put(route('users.connected.update.biography'), [
            'biography' => '<p>Ingénieur <a href="javascript:alert(1)">piège</a></p>',
            'activities' => '<ol><li>Recherche</li></ol><iframe src="//x"></iframe>',
        ])->assertSessionHas('success');

        $member->refresh();
        $this->assertSame('<p>Ingénieur <a>piège</a></p>', $member->biography);
        $this->assertSame('<ol><li>Recherche</li></ol>', $member->activities);
    }
}
