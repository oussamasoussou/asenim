<?php

namespace Tests\Feature;

use App\Models\Documents;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(array $attrs = []): User
    {
        return User::factory()->create(array_merge(['role' => 'admin', 'phone' => '0600000001'], $attrs));
    }

    private function membre(array $attrs = []): User
    {
        return User::factory()->create(array_merge(['role' => 'membre', 'phone' => '0700000002'], $attrs));
    }

    // ------------------------------------------------------------------ Auth

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get('/index')->assertRedirect(route('login'));
        $this->get('/documents/non-archived')->assertRedirect(route('login'));
        $this->get('/news')->assertRedirect(route('login'));
        $this->get('/chat')->assertRedirect(route('login'));
        $this->get('/users/security')->assertRedirect(route('login'));
    }

    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_admin_login_redirects_to_index(): void
    {
        $admin = $this->admin();
        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('index'));
        $this->assertTrue((bool) $admin->fresh()->user_connected);
    }

    public function test_membre_first_connection_redirects_to_first_connection_form(): void
    {
        $membre = $this->membre(['first_connection' => 1]);
        $this->post('/login', ['email' => $membre->email, 'password' => 'password'])
            ->assertRedirect(route('user.edit.personnel.membre.first'));
    }

    public function test_wrong_password_is_rejected(): void
    {
        $admin = $this->admin();
        $this->post('/login', ['email' => $admin->email, 'password' => 'nope'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ------------------------------------------------------------------ Pages

    public function test_admin_pages_render(): void
    {
        $admin = $this->admin();
        $other = $this->membre();

        foreach ([
            '/index', '/dashboard', '/list-users', '/list-users-archives', '/store-user',
            "/users/{$other->id}/edit", "/users/{$other->id}/edit/professionnelle", "/users/{$other->id}/edit/biography",
            '/user/editConnectedUser', '/users/editConnectedUser/professionnelle', '/users/editConnectedUser/biography',
            '/users/security', '/documents/non-archived', '/documents/archived', '/documents/create-document',
            '/news', '/news/archived', '/news/create', '/chat',
        ] as $url) {
            $response = $this->actingAs($admin)->get($url);
            $this->assertContains($response->status(), [200, 302], "GET $url => {$response->status()}");
            if ($url !== '/dashboard') {
                $response->assertOk();
            }
        }
    }

    public function test_membre_can_access_own_profile_but_not_admin_pages(): void
    {
        $membre = $this->membre();

        foreach ([
            '/index', '/user/editConnectedUser', '/users/editConnectedUser/professionnelle',
            '/users/editConnectedUser/biography', '/users/security', '/documents/non-archived', '/news', '/chat',
            '/user/first-connection/personnelle', '/user/first-connection/professionnelle', '/user/first-connection/biography',
        ] as $url) {
            $this->actingAs($membre)->get($url)->assertOk();
        }

        $this->actingAs($membre)->get('/list-users')->assertRedirect(route('index'));
    }

    public function test_users_list_ignores_invalid_sort_and_pagination(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->get('/list-users?sort=foo;drop&order=sideways&perPage=0&page=-3')->assertOk();
        $this->actingAs($admin)->get('/list-users-archives?sort=bar&perPage=0')->assertOk();
        $this->actingAs($admin)->get('/documents/non-archived?sort=nope&perPage=0')->assertOk();
    }

    // ------------------------------------------------------------------ Profil connecté

    public function test_connected_user_can_update_personal_info_with_phone_starting_with_zero(): void
    {
        $membre = $this->membre();

        $this->actingAs($membre)
            ->from('/user/editConnectedUser')
            ->put(route('users.connected.update'), [
                'first_name' => 'jean',
                'last_name' => 'DUPONT',
                'email' => $membre->email,
                'phone' => '06 12 34 56 78',
            ])
            ->assertRedirect('/user/editConnectedUser')
            ->assertSessionHas('success');

        $membre->refresh();
        $this->assertSame('JEAN', $membre->first_name);
        $this->assertSame('Dupont', $membre->last_name);
        $this->assertSame('06 12 34 56 78', $membre->phone);
        $this->assertSame('JD', $membre->name);
    }

    public function test_connected_user_can_update_professional_info_and_biography(): void
    {
        $membre = $this->membre(['first_connection' => 1]);

        $this->actingAs($membre)->put(route('users.connected.update.professionnelle'), [
            'institution' => 'Univ', 'grade' => '2', 'orcid' => '0000-0001', 'function' => 'Chercheur',
        ])->assertSessionHas('success');

        $this->actingAs($membre)->put(route('users.connected.update.biography'), [
            'biography' => 'Bio', 'activities' => 'Act',
        ])->assertSessionHas('success');

        $membre->refresh();
        $this->assertSame('Univ', $membre->institution);
        $this->assertSame('Bio', $membre->biography);
        // la page profil ne doit pas modifier le flag de première connexion
        $this->assertEquals(1, $membre->first_connection);
    }

    public function test_user_can_change_password(): void
    {
        $membre = $this->membre();

        $this->actingAs($membre)->put(route('users.update.password'), [
            'current_password' => 'wrong',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ])->assertSessionHasErrors('current_password');

        $this->actingAs($membre)->put(route('users.update.password'), [
            'current_password' => 'password',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword',
        ])->assertSessionHas('success');

        $this->assertTrue(Hash::check('newpassword', $membre->fresh()->password));
    }

    public function test_first_connection_flow(): void
    {
        $membre = $this->membre(['first_connection' => 1]);

        $this->actingAs($membre)->put(route('user.update.personnel.membre.first'), [
            'first_name' => 'a', 'last_name' => 'b', 'email' => $membre->email, 'phone' => '0711111111',
        ])->assertRedirect(route('user.edit.professionnelle.membre.first'));

        $this->actingAs($membre)->post(route('user.update.professionnelle.membre.first'), ['institution' => 'X'])
            ->assertRedirect(route('user.edit.biography.membre.first'));

        $this->actingAs($membre)->post(route('user.update.biography.membre.first'), ['biography' => 'Y'])
            ->assertRedirect(route('index'));

        $this->assertEquals(0, $membre->fresh()->first_connection);
    }

    // ------------------------------------------------------------------ Admin

    public function test_admin_can_create_update_delete_and_restore_user(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('create-user'), [
            'first_name' => 'paul', 'last_name' => 'MARTIN', 'phone' => '0699999999',
            'email' => 'paul@example.com', 'role' => 'membre', 'type' => 'permanent',
        ])->assertRedirect(route('users.index'))->assertSessionHas('success');

        $user = User::where('email', 'paul@example.com')->firstOrFail();

        $this->actingAs($admin)->put(route('users.update', $user->id), [
            'first_name' => 'pierre', 'last_name' => 'martin', 'email' => 'paul@example.com',
            'phone' => '0688888888', 'role' => 'membre', 'type' => 'non_permanent',
        ])->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('PIERRE', $user->first_name);
        $this->assertSame('0688888888', $user->phone);
        $this->assertSame('PM', $user->name);

        $this->actingAs($admin)->post(route('users.reset_password', $user->id))->assertSessionHas('success');
        $this->actingAs($admin)->delete(route('users.destroy', $user->id))->assertSessionHas('success');
        $this->assertSoftDeleted($user);
        $this->actingAs($admin)->post(route('users.restore', $user->id))->assertSessionHas('success');
        $this->assertNotSoftDeleted($user);

        $this->actingAs($admin)->post(route('users.restore', 999999))->assertSessionHas('error');
    }

    // ------------------------------------------------------------------ Documents

    public function test_document_crud_and_ownership(): void
    {
        Storage::fake('public');
        $owner = $this->membre();
        $intruder = $this->membre(['phone' => '0733333333']);

        $this->actingAs($owner)->post(route('documents.store'), [
            'file_name' => 'Doc', 'member_type' => 'all_members',
            'file' => UploadedFile::fake()->create('a.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('documents.non_archived'));

        $doc = Documents::firstOrFail();
        Storage::disk('public')->assertExists($doc->file_path);
        $oldPath = $doc->file_path;

        $this->actingAs($intruder)->get(route('documents.edit', $doc->id))->assertForbidden();
        $this->actingAs($intruder)->delete(route('documents.delete', $doc->id))->assertForbidden();

        $this->actingAs($owner)->get(route('documents.edit', $doc->id))->assertOk();
        $this->actingAs($owner)->put(route('documents.update', $doc->id), [
            'file_name' => 'Doc 2', 'member_type' => 'permanent',
            'file' => UploadedFile::fake()->create('b.pdf', 100, 'application/pdf'),
        ])->assertRedirect(route('documents.non_archived'));

        $doc->refresh();
        $this->assertSame('Doc 2', $doc->file_name);
        $this->assertNotSame($oldPath, $doc->file_path);
        Storage::disk('public')->assertExists($doc->file_path);
        Storage::disk('public')->assertMissing($oldPath);

        $this->actingAs($owner)->delete(route('documents.delete', $doc->id))->assertRedirect();
        $this->assertSoftDeleted($doc);
        $this->actingAs($owner)->post(route('documents.restore', $doc->id))->assertRedirect();
        $this->assertNotSoftDeleted($doc);
    }

    public function test_archived_documents_pagination_total_is_correct(): void
    {
        $admin = $this->admin();
        for ($i = 0; $i < 3; $i++) {
            Documents::create(['file_name' => "d$i", 'file_path' => "documents/d$i.pdf", 'user_id' => $admin->id])->delete();
        }

        $this->actingAs($admin)
            ->getJson('/documents/archived?perPage=2&page=2', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertJson(['totalDocuments' => 3, 'totalPages' => 2]);
    }

    // ------------------------------------------------------------------ News

    public function test_news_crud_and_ownership(): void
    {
        $owner = $this->membre();
        $intruder = $this->membre(['phone' => '0744444444']);

        $this->actingAs($owner)->post(route('news.store'), [
            'title' => 'T', 'events_news' => 'news', 'content' => 'C', 'date' => '2026-01-01',
        ])->assertRedirect(route('news.index', ['tab' => 'news']));

        $news = News::firstOrFail();

        $this->actingAs($intruder)->get(route('news.edit', $news->id))->assertForbidden();
        $this->actingAs($owner)->get(route('news.edit', $news->id))->assertOk();
        $this->actingAs($owner)->put(route('news.update', $news->id), [
            'title' => 'T2', 'events_news' => 'event', 'content' => 'C2', 'date' => '2026-12-24T18:30',
        ])->assertRedirect(route('news.index', ['tab' => 'events']));
        $this->assertSame('T2', $news->fresh()->title);
        $this->assertStringStartsWith('2026-12-24 18:30', (string) $news->fresh()->date);

        $this->actingAs($owner)->delete(route('news.delete', $news->id))->assertRedirect();
        $this->assertSoftDeleted($news);
        $this->actingAs($owner)->post(route('news.restore', $news->id))->assertRedirect();
        $this->assertNotSoftDeleted($news);
    }

    // ------------------------------------------------------------------ Chat

    public function test_chat_send_and_fetch_messages(): void
    {
        $membre = $this->membre();
        $this->actingAs($membre)->postJson('/messages', ['content' => 'Bonjour'])->assertOk();
        $this->actingAs($membre)->getJson('/messages')->assertOk()->assertJsonFragment(['content' => 'Bonjour']);
    }
}
