<?php

namespace Tests\Feature\Admin;

use App\Http\Livewire\UserTable;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function admin(): User
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole('admin');

        return $admin;
    }

    public function test_admin_can_create_a_dosen_user(): void
    {
        $response = $this->actingAs($this->admin())->post(route('users.store'), [
            'name' => 'Budi Santoso',
            'nidn' => '1234567890',
            'email' => 'budi@example.com',
            'progdi' => 'Teknik Informatika',
            'campus_origin' => 'Universitas Sains dan Teknologi Komputer',
            'role' => 'dosen',
            'assessor_fee' => 2,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'nidn' => '1234567890',
            'progdi' => 'Teknik Informatika',
            'campus_origin' => 'Universitas Sains dan Teknologi Komputer',
        ]);
        $this->assertTrue(User::where('email', 'budi@example.com')->first()->hasRole('dosen'));
    }

    /**
     * Regression test: the "Eksternal" status option on the create form
     * sends 'external', but the users.status enum only allowed 'eksternal'
     * until tonight's migration - the insert failed silently under strict
     * SQL mode and landed in a dd()-based catch that dumped raw output
     * instead of saving anything.
     */
    public function test_admin_can_create_an_asesor_with_external_status(): void
    {
        $response = $this->actingAs($this->admin())->post(route('users.store'), [
            'name' => 'Siti Asesor',
            'nidn' => '9988776655',
            'email' => 'siti@example.com',
            'progdi' => 'Sistem Informasi',
            'role' => 'asesor',
            'status' => 'external',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'siti@example.com',
            'status' => 'external',
        ]);
    }

    /**
     * Regression test: re-adding a user with the email/NIDN of a
     * previously (soft-)deleted user used to fail with a false "already
     * taken" error, since the unique validation and the DB's unique index
     * on email don't know about deleted_at.
     */
    public function test_creating_user_with_email_of_soft_deleted_user_restores_instead_of_failing(): void
    {
        $trashed = User::factory()->create(['email' => 'lama@example.com', 'nidn' => '111']);
        $trashed->delete();

        $response = $this->actingAs($this->admin())->post(route('users.store'), [
            'name' => 'Nama Baru',
            'nidn' => '111',
            'email' => 'lama@example.com',
            'progdi' => 'Teknik Informatika',
            'role' => 'dosen',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertSame(1, User::withTrashed()->where('email', 'lama@example.com')->count());
        $this->assertDatabaseHas('users', [
            'id' => $trashed->id,
            'email' => 'lama@example.com',
            'name' => 'Nama Baru',
            'deleted_at' => null,
        ]);
    }

    public function test_a_genuinely_active_duplicate_email_is_still_rejected(): void
    {
        User::factory()->create(['email' => 'aktif@example.com', 'nidn' => '222']);

        $response = $this->actingAs($this->admin())->post(route('users.store'), [
            'name' => 'Orang Lain',
            'nidn' => '333',
            'email' => 'aktif@example.com',
            'progdi' => 'Teknik Informatika',
            'role' => 'dosen',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_non_admin_cannot_create_users(): void
    {
        $dosen = User::factory()->create(['is_active' => true]);
        $dosen->assignRole('dosen');

        $response = $this->actingAs($dosen)->post(route('users.store'), [
            'name' => 'Test',
            'nidn' => '1',
            'email' => 'test@example.com',
            'role' => 'dosen',
        ]);

        $response->assertForbidden();
    }

    /**
     * Regression test: Auth::login() during impersonation didn't refresh
     * the session's stored password hash, so Jetstream's AuthenticateSession
     * middleware treated the very next request as a hijacked session and
     * immediately logged back out to /login instead of landing on the
     * impersonated user's dashboard.
     */
    public function test_admin_can_login_as_another_user_and_return(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create(['is_active' => true]);
        $target->assignRole('dosen');

        $this->actingAs($admin);

        Livewire::test(UserTable::class)
            ->call('loginAs', $target->id)
            ->assertRedirect(route('dashboard.index'));

        $this->assertAuthenticatedAs($target);
        $this->assertSame($admin->id, session('impersonator_id'));
        $this->assertSame(
            $target->getAuthPassword(),
            session('password_hash_' . auth()->getDefaultDriver())
        );

        $this->post(route('login-as.stop'))->assertRedirect(route('users.index'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_cannot_login_as_another_admin(): void
    {
        $admin = $this->admin();
        $otherAdmin = User::factory()->create(['is_active' => true]);
        $otherAdmin->assignRole('admin');

        $this->actingAs($admin);

        Livewire::test(UserTable::class)->call('loginAs', $otherAdmin->id);

        $this->assertAuthenticatedAs($admin);
    }
}
