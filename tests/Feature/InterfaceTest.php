<?php

namespace Tests\Feature;

use App\Models\ChatGroupe;
use App\Models\Documents;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Vérifie le rendu de l'interface : pages avec / sans données, états vides,
 * messages flash, échappement des contenus utilisateur et pages d'erreur.
 */
class InterfaceTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'phone' => '0600000001', 'first_name' => 'Alice', 'last_name' => 'Martin']);
    }

    private function seedContent(User $author): void
    {
        News::create(['title' => 'Annonce <b>importante</b>', 'events_news' => 'news', 'content' => "Ligne 1\nLigne 2", 'date' => now(), 'user_id' => $author->id]);
        News::create(['title' => 'Séminaire', 'events_news' => 'event', 'content' => 'Salle A', 'date' => now()->addWeek(), 'user_id' => $author->id]);
        News::create(['title' => 'Ancien événement', 'events_news' => 'event', 'content' => 'Passé', 'date' => now()->subMonth(), 'user_id' => $author->id]);
        Documents::create(['file_name' => 'Rapport annuel', 'file_path' => 'documents/rapport.pdf', 'member_type' => 'permanent', 'user_id' => $author->id]);
        Documents::create(['file_name' => 'Photo', 'file_path' => 'documents/photo.jpg', 'member_type' => 'all_members', 'user_id' => $author->id]);
        ChatGroupe::create(['user_id' => $author->id, 'content' => '<script>alert(1)</script>']);
    }

    public function test_every_page_renders_empty_states_without_data(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/index')->assertOk()
            ->assertSee('Aucune actualité pour le moment')
            ->assertSee('Aucun événement programmé')
            ->assertSee('Aucun document partagé');

        $this->actingAs($admin)->get('/documents/non-archived')->assertOk()->assertSee('Aucun document pour le moment');
        $this->actingAs($admin)->get('/documents/archived')->assertOk()->assertSee('Aucun document archivé');
        $this->actingAs($admin)->get('/news')->assertOk()->assertSee('Aucune actualité');
        $this->actingAs($admin)->get('/news?tab=events')->assertOk()->assertSee('Aucun événement');
        $this->actingAs($admin)->get('/news/archived')->assertOk()->assertSee('Aucune publication archivée');
        $this->actingAs($admin)->get('/list-users-archives')->assertOk()->assertSee('Aucun compte archivé');
        $this->actingAs($admin)->get('/list-users?search=zzzz')->assertOk()->assertSee('Aucun résultat');
    }

    public function test_pages_render_with_data_and_escape_user_content(): void
    {
        $admin = $this->admin();
        $member = User::factory()->create(['role' => 'membre', 'phone' => '0700000002', 'first_name' => 'Bob', 'last_name' => 'Moreau', 'image' => null]);
        $this->seedContent($member);

        $this->actingAs($admin)->get('/index')->assertOk()
            ->assertSee('Annonce &lt;b&gt;importante&lt;/b&gt;', false)
            ->assertDontSee('Annonce <b>importante</b>', false)
            ->assertSee('Séminaire')
            ->assertDontSee('Ancien événement')
            ->assertSee('Rapport annuel');

        $this->actingAs($admin)->get('/news?tab=events')->assertOk()
            ->assertSee('Séminaire')->assertSee('Ancien événement')->assertSee('Passé');

        $this->actingAs($admin)->get('/documents/non-archived')->assertOk()
            ->assertSee('Rapport annuel')->assertSee('Permanents (GDI)')->assertSee('is-pdf', false)->assertSee('is-image', false);

        $this->actingAs($admin)->get('/list-users')->assertOk()->assertSee('Bob')->assertSee('BM'); // initiales en l'absence de photo
    }

    public function test_live_search_returns_html_region_for_lists(): void
    {
        $admin = $this->admin();
        User::factory()->create(['first_name' => 'Zoé', 'last_name' => 'Durand', 'phone' => '0711111111']);

        $this->actingAs($admin)->get('/list-users?search=Zo')->assertOk()
            ->assertSee('id="users-list"', false)->assertSee('Zoé')->assertDontSee('Alice Martin</a>', false);
    }

    public function test_actions_only_shown_to_owner_or_admin(): void
    {
        $owner = User::factory()->create(['phone' => '0722222222']);
        $other = User::factory()->create(['phone' => '0733333333']);
        $doc = Documents::create(['file_name' => 'Privé', 'file_path' => 'documents/p.pdf', 'user_id' => $owner->id]);

        // Un membre ne voit que ses documents ; l'admin voit tout avec les actions
        $this->actingAs($owner)->get('/documents/non-archived')->assertSee(route('documents.edit', $doc->id));
        $this->actingAs($other)->get('/documents/non-archived')->assertDontSee(route('documents.edit', $doc->id));
    }

    public function test_flash_messages_are_exposed_to_toasts(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->withSession(['success' => 'Bravo !'])->get('/index')
            ->assertSee('<script type="application/json" id="ui-flash">{"success":"Bravo !"}</script>', false);
    }

    public function test_membre_redirected_from_admin_area_with_error_toast(): void
    {
        $member = User::factory()->create(['phone' => '0744444444']);
        $this->actingAs($member)->get('/list-users')->assertRedirect(route('index'))->assertSessionHas('error');
    }

    public function test_legacy_profile_page_redirects_to_profile_tabs(): void
    {
        $member = User::factory()->create(['phone' => '0755555555']);
        $this->actingAs($member)->get(route('user.edit.connected.membre.user', $member->id))
            ->assertRedirect(route('users.editConnectedUser'));
    }

    public function test_login_page_shows_errors(): void
    {
        $this->from('/login')->post('/login', ['email' => 'nobody@example.com', 'password' => 'x'])
            ->assertRedirect('/login');
        $this->get('/login')->assertSee('Les identifiants fournis sont incorrects.');
    }

    public function test_custom_error_pages(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->get('/users/999999/edit')->assertNotFound()->assertSee('Page introuvable');

        $owner = User::factory()->create(['phone' => '0766666666']);
        $doc = Documents::create(['file_name' => 'X', 'file_path' => 'documents/x.pdf', 'user_id' => $owner->id]);
        $intruder = User::factory()->create(['phone' => '0777777777']);
        $this->actingAs($intruder)->get(route('documents.edit', $doc->id))->assertForbidden()->assertSee('Accès refusé');
    }

    public function test_all_lists_paginate_five_items_by_default(): void
    {
        $admin = $this->admin();
        for ($i = 1; $i <= 7; $i++) {
            User::factory()->create(['phone' => '06100000' . $i, 'first_name' => 'Membre' . $i]);
            Documents::create(['file_name' => 'Doc ' . $i, 'file_path' => "documents/d{$i}.pdf", 'user_id' => $admin->id]);
            News::create(['title' => 'Actu ' . $i, 'events_news' => 'news', 'content' => 'x', 'date' => now(), 'user_id' => $admin->id]);
        }

        $count = fn ($html, $needle) => substr_count($html, $needle);

        // Utilisateurs : 7 membres (l'admin connecté est exclu) → 5 + 2
        $p1 = $this->actingAs($admin)->get('/list-users')->assertOk()->assertSee('7 résultats')->getContent();
        $this->assertSame(5, $count($p1, 'class="user-cell-name'));
        $p2 = $this->actingAs($admin)->get('/list-users?page=2')->getContent();
        $this->assertSame(2, $count($p2, 'class="user-cell-name'));

        // Documents
        $d1 = $this->actingAs($admin)->get('/documents/non-archived')->assertOk()->getContent();
        $this->assertSame(5, $count($d1, 'class="user-cell-name'));
        $this->assertStringContainsString('page=2', $d1);

        // Actualités : pagination + compteur d'onglet sur le total
        $n1 = $this->actingAs($admin)->get('/news')->assertOk()->getContent();
        $this->assertSame(5, $count($n1, 'class="news-item"'));
        $this->assertStringContainsString('<span class="tab-count">7</span>', $n1);
        $n2 = $this->actingAs($admin)->get('/news?page=2')->getContent();
        $this->assertSame(2, $count($n2, 'class="news-item"'));

        // Recherche côté serveur sur les actualités
        $s = $this->actingAs($admin)->get('/news?search=Actu+3')->getContent();
        $this->assertSame(1, $count($s, 'class="news-item"'));

        // Page hors limites → dernière page disponible
        $last = $this->actingAs($admin)->get('/news?page=99')->getContent();
        $this->assertSame(2, $count($last, 'class="news-item"'));
    }

    public function test_validation_messages_are_in_french(): void
    {
        $member = User::factory()->create(['phone' => '0799999999']);
        $this->actingAs($member)->post(route('documents.store'), [])
            ->assertSessionHasErrors(['file' => 'Le champ fichier est obligatoire.', 'file_name' => 'Le champ titre du document est obligatoire.']);
    }

    public function test_empty_chat_message_is_rejected(): void
    {
        $member = User::factory()->create(['phone' => '0788888888']);
        $this->actingAs($member)->postJson('/messages', ['content' => ''])->assertStatus(422);
    }
}
