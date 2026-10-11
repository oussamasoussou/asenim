<?php

namespace Tests\Feature;

use App\Models\CompanyInfo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyInfoTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'phone' => '0600000001']);
    }

    public function test_admin_sees_empty_state_then_saves_company_details(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('company.edit'))->assertOk()
            ->assertSee('Coordonnées de l’entreprise')
            ->assertSee('Aucune coordonnée renseignée');

        $this->actingAs($admin)->put(route('company.update'), [
            'address' => "12 rue de la République\n69002 Lyon",
            'phone' => '04 78 00 00 00',
            'email' => 'contact@Asenim.fr',
        ])->assertRedirect(route('company.edit'))->assertSessionHas('success');

        $this->assertDatabaseCount('company_infos', 1);
        $company = CompanyInfo::current();
        $this->assertSame('04 78 00 00 00', $company->phone);
        $this->assertSame($admin->id, $company->updated_by);

        $this->actingAs($admin)->get(route('company.edit'))->assertOk()
            ->assertSee('href="tel:0478000000"', false)
            ->assertSee('href="mailto:contact@Asenim.fr"', false)
            ->assertSee('Voir sur la carte');
    }

    public function test_update_keeps_a_single_record(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->put(route('company.update'), ['email' => 'a@a.fr']);
        $this->actingAs($admin)->put(route('company.update'), ['email' => 'b@b.fr', 'phone' => '+33 4 78 00 00 00']);

        $this->assertDatabaseCount('company_infos', 1);
        $this->assertSame('b@b.fr', CompanyInfo::current()->email);
    }

    public function test_validation_errors_are_in_french(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->put(route('company.update'), ['email' => 'pas-un-email', 'phone' => 'abc'])
            ->assertSessionHasErrors([
                'email' => 'Le champ adresse e-mail doit être une adresse e-mail valide.',
                'phone' => 'Le numéro de téléphone ne doit contenir que des chiffres, espaces et les caractères + ( ) . -',
            ]);
        $this->assertDatabaseCount('company_infos', 0);
    }

    public function test_only_admins_can_access(): void
    {
        $member = User::factory()->create(['role' => 'membre', 'phone' => '0700000002']);

        $this->actingAs($member)->get(route('company.edit'))->assertRedirect(route('index'))->assertSessionHas('error');
        $this->actingAs($member)->put(route('company.update'), ['email' => 'x@x.fr'])->assertRedirect(route('index'));
        $this->assertDatabaseCount('company_infos', 0);

        // Le lien n'apparaît pas dans le menu d'un membre
        $this->actingAs($member)->get('/index')->assertDontSee(route('company.edit'));
        $this->actingAs($this->admin())->get('/index')->assertSee(route('company.edit'));

        auth()->logout();
        $this->get(route('company.edit'))->assertRedirect(route('login'));
    }

    public function test_admin_saves_social_links_shown_on_public_site(): void
    {
        $admin = $this->admin();

        // Aucun lien : pas d'icônes sociales sur le site public (plus de liens « # »)
        $html = $this->get('/contact')->getContent();
        $this->assertStringNotContainsString('SUIVEZ-NOUS', $html);
        $this->assertStringNotContainsString('asenim-footer__social"', $html);

        $this->actingAs($admin)->put(route('company.update'), [
            'email' => 'contact@asenim.org',
            'facebook_url' => 'facebook.com/asenim',                       // https:// ajouté automatiquement
            'linkedin_url' => 'https://www.linkedin.com/company/asenim',
            'youtube_url' => '',
            'instagram_url' => 'https://instagram.com/asenim',
        ])->assertRedirect(route('company.edit'))->assertSessionHas('success');

        $company = CompanyInfo::current();
        $this->assertSame('https://facebook.com/asenim', $company->facebook_url);
        $this->assertNull($company->youtube_url);
        $this->assertSame(['facebook', 'linkedin', 'instagram'], array_column($company->socialLinks(), 'key'));

        // Admin : aperçu
        $this->actingAs($admin)->get(route('company.edit'))->assertSee('https://www.linkedin.com/company/asenim');

        // Site public : pied de page, menu, page contact et accueil
        foreach (['/', '/contact', '/actualites'] as $url) {
            $html = $this->get($url)->getContent();
            $this->assertStringContainsString('href="https://facebook.com/asenim" target="_blank" rel="noopener"', $html, $url);
            $this->assertStringContainsString('href="https://instagram.com/asenim"', $html, $url);
            $this->assertStringNotContainsString('fa-youtube', $html, $url);   // non renseigné → masqué
            $this->assertStringNotContainsString('fa-twitter', $html, $url);   // Twitter n'est pas géré
        }
        $this->assertStringContainsString('SUIVEZ-NOUS', $this->get('/contact')->getContent());
    }

    public function test_social_links_must_point_to_the_right_network(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->put(route('company.update'), [
            'facebook_url' => 'https://linkedin.com/in/x',
            'youtube_url' => 'javascript:alert(1)',
            'instagram_url' => 'pas un lien',
        ])->assertSessionHasErrors([
            'facebook_url' => 'Le lien Facebook doit pointer vers facebook.com ou fb.com ou fb.me.',
            'youtube_url',
            'instagram_url',
        ]);
        $this->assertDatabaseCount('company_infos', 0);

        // Variantes d'adresse acceptées
        $this->actingAs($admin)->put(route('company.update'), [
            'facebook_url' => 'https://m.facebook.com/asenim',
            'youtube_url' => 'https://youtu.be/abc',
            'linkedin_url' => 'https://fr.linkedin.com/company/asenim',
        ])->assertSessionHasNoErrors();
    }
}
