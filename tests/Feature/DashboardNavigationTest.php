<?php

namespace Tests\Feature;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_student_can_open_equipment_list(): void
    {
        foreach (['Admin', 'Siswa'] as $role) {
            $user = User::factory()->create([
                'username' => strtolower($role).'-uji',
                'role' => $role,
            ]);

            $this->actingAs($user)
                ->get(route('dashboard', ['section' => 'alat']))
                ->assertOk()
                ->assertSeeText('Daftar Alat')
                ->assertSeeText($role)
                ->assertSeeText('Input Peminjaman')
                ->assertSeeText('Laptop');
        }
    }

    public function test_history_page_only_shows_returned_or_late_loans(): void
    {
        $user = User::factory()->create([
            'username' => 'admin-uji',
            'role' => 'Admin',
        ]);

        Peminjaman::create([
            'nama_alat' => 'Laptop',
            'borrower' => 'peminjam-aktif-uji',
            'tanggal_pinjam' => '2026-11-18',
            'status' => 'Dipinjam',
        ]);
        Peminjaman::create([
            'nama_alat' => 'Proyektor LCD / DLP',
            'borrower' => 'peminjam-riwayat-uji',
            'tanggal_pinjam' => '2026-11-17',
            'status' => 'Dikembalikan',
        ]);

        $this->actingAs($user)
            ->get(route('dashboard', ['section' => 'riwayat']))
            ->assertOk()
            ->assertSeeText('Riwayat Peminjaman')
            ->assertSeeText('peminjam-riwayat-uji')
            ->assertDontSee('peminjam-aktif-uji');
    }
}
