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
}
