<?php

namespace Tests\Feature;

use App\Models\BoardMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BoardMemberTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'phone' => '0600000001']);
    }

    public function test_migration_imports_the_members_of_the_original_site(): void
    {
        $this->assertSame(['Maher BenChiekh', 'Lamis Allegue', 'Faten Debbabi'], BoardMember::ordered()->pluck('full_name')->all());

        $this->get('/bureau-executif')->assertOk()->assertSee('Maher BenChiekh')->assertSee('Trésorière');
        $home = $this->get('/')->assertOk()->getContent();
        $this->assertSame(6, substr_count($home, 'class="team-member swiper-slide"')); // complété à 6 pour la boucle
        $this->assertStringContainsString('« Créer du lien, aujourd&#039;hui et pour demain. »', $home);
    }

    public function test_admin_creates_edits_and_removes_a_member_with_photo(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('board.index'))->assertOk()->assertSee('Lamis Allegue');
        $this->actingAs($admin)->get(route('board.create'))->assertOk();

        $this->actingAs($admin)->post(route('board.store'), [
            'full_name' => 'Sonia Gharbi', 'role' => 'Vice-présidente', 'organization' => 'ENIMonastir',
            'email' => 'sonia@asenim.tn', 'quote' => '« Avancer ensemble »', 'is_visible' => '1',
            'photo' => UploadedFile::fake()->image('sonia.jpg', 400, 400),
        ])->assertRedirect(route('board.index'))->assertSessionHas('success');

        $member = BoardMember::where('full_name', 'Sonia Gharbi')->firstOrFail();
        $this->assertSame(4, $member->position); // ajouté en fin de liste
        Storage::disk('public')->assertExists($member->photo);
        $this->assertSame('« Avancer ensemble »', $member->displayQuote());

        $this->get('/bureau-executif')->assertSee('Sonia Gharbi')->assertSee('href="mailto:sonia@asenim.tn"', false);

        // Modification + remplacement de la photo
        $oldPhoto = $member->photo;
        $this->actingAs($admin)->put(route('board.update', $member->id), [
            'full_name' => 'Sonia Gharbi', 'role' => 'Présidente', 'is_visible' => '1',
            'photo' => UploadedFile::fake()->image('new.png', 300, 300),
        ])->assertRedirect(route('board.index'));
        $member->refresh();
        $this->assertSame('Présidente', $member->role);
        Storage::disk('public')->assertMissing($oldPhoto);
        Storage::disk('public')->assertExists($member->photo);

        // Suppression de la photo seule
        $this->actingAs($admin)->put(route('board.update', $member->id), [
            'full_name' => 'Sonia Gharbi', 'role' => 'Présidente', 'is_visible' => '1', 'remove_photo' => '1',
        ]);
        $this->assertNull($member->fresh()->photo);

        // Retrait du bureau
        $this->actingAs($admin)->delete(route('board.destroy', $member->id))->assertSessionHas('success');
        $this->assertDatabaseMissing('board_members', ['full_name' => 'Sonia Gharbi']);
        $this->get('/bureau-executif')->assertDontSee('Sonia Gharbi');
    }

    public function test_order_and_visibility_drive_the_public_site(): void
    {
        $admin = $this->admin();
        $faten = BoardMember::where('full_name', 'Faten Debbabi')->firstOrFail();

        // Faten remonte en 2e position
        $this->actingAs($admin)->post(route('board.move', [$faten->id, 'up']));
        $this->assertSame(['Maher BenChiekh', 'Faten Debbabi', 'Lamis Allegue'], BoardMember::ordered()->pluck('full_name')->all());

        $html = $this->get('/bureau-executif')->getContent();
        $this->assertLessThan(strpos($html, 'Lamis Allegue'), strpos($html, 'Faten Debbabi'));

        // Le premier ne peut pas monter plus haut
        $maher = BoardMember::where('full_name', 'Maher BenChiekh')->firstOrFail();
        $this->actingAs($admin)->post(route('board.move', [$maher->id, 'up']));
        $this->assertSame('Maher BenChiekh', BoardMember::ordered()->value('full_name'));

        // Masquer un membre : absent du site, toujours présent dans l'admin
        $this->actingAs($admin)->post(route('board.toggle', $faten->id))->assertSessionHas('success');
        $this->assertFalse($faten->fresh()->is_visible);
        $this->get('/bureau-executif')->assertDontSee('Faten Debbabi');
        $this->get('/')->assertDontSee('Faten Debbabi');
        $this->actingAs($admin)->get(route('board.index'))->assertSee('Faten Debbabi')->assertSee('Masqué');
    }

    public function test_public_search_and_empty_states(): void
    {
        $this->get('/bureau-executif?q=trésor')->assertSee('Faten Debbabi')->assertDontSee('Lamis Allegue');
        $this->get('/bureau-executif?q=zzz')->assertSee('Aucun membre trouvé');

        BoardMember::query()->delete();
        $this->get('/bureau-executif')->assertSee('Bureau exécutif bientôt présenté');
        $this->get('/')->assertOk()->assertDontSee('id="team-carousel"', false); // section masquée sur l'accueil
    }

    public function test_validation_and_access(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('board.store'), ['email' => 'pas-un-email'])
            ->assertSessionHasErrors([
                'full_name' => 'Le champ nom complet est obligatoire.',
                'role' => 'Le champ fonction est obligatoire.',
                'email',
            ]);

        $member = User::factory()->create(['phone' => '0700000002']);
        $this->actingAs($member)->get(route('board.index'))->assertRedirect(route('index'));
        $this->actingAs($member)->post(route('board.store'), ['full_name' => 'X', 'role' => 'Y'])->assertRedirect(route('index'));
        $this->assertSame(3, BoardMember::count());
    }
}
