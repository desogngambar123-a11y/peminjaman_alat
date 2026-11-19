<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_new_user_can_register_and_log_in_with_the_password(): void
    {
        $response = $this->post(route('register.submit'), [
            'username' => 'siswa-baru',
            'email' => 'siswa-baru@example.test',
            'password' => 'rahasia123',
            'role' => 'Siswa',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Registrasi berhasil, silakan login.');
        $this->assertDatabaseHas('users', [
            'username' => 'siswa-baru',
            'email' => 'siswa-baru@example.test',
            'role' => 'Siswa',
        ]);
        $this->assertTrue(Hash::check('rahasia123', User::where('username', 'siswa-baru')->value('password')));
    }

    public function test_duplicate_username_shows_a_clear_error_and_keeps_the_input(): void
    {
        User::factory()->create([
            'username' => 'username-terpakai',
            'email' => 'lama@example.test',
        ]);

        $response = $this->from(route('register'))->post(route('register.submit'), [
            'username' => 'username-terpakai',
            'email' => 'baru@example.test',
            'password' => 'rahasia123',
            'role' => 'Siswa',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors([
            'username' => 'Username sudah digunakan. Silakan pilih username lain.',
        ]);
        $response->assertSessionHas('_old_input.username', 'username-terpakai');
        $this->assertDatabaseCount('users', 1);
    }

    public function test_public_registration_cannot_create_an_admin_account(): void
    {
        $this->post(route('register.submit'), [
            'username' => 'akun-terdaftar',
            'email' => 'akun-terdaftar@example.test',
            'password' => 'rahasia123',
            'role' => 'Admin',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'username' => 'akun-terdaftar',
            'role' => 'Siswa',
        ]);
    }
}
