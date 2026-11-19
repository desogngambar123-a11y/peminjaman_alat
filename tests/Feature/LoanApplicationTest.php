<?php

namespace Tests\Feature;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_loan_submission_waits_for_admin_verification_and_uses_the_signed_in_user(): void
    {
        $student = User::factory()->create([
            'username' => 'siswa-peminjam',
            'role' => 'Siswa',
        ]);

        $this->actingAs($student)
            ->post(route('loans.store'), [
                'alat' => 'Laptop',
                'borrower' => 'nama-yang-dipalsukan',
                'tanggal_pinjam' => '2026-11-20',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('peminjamans', [
            'nama_alat' => 'Laptop',
            'borrower' => 'siswa-peminjam',
            'status' => 'Menunggu Verifikasi',
        ]);
    }

    public function test_admin_can_approve_a_pending_loan_and_record_a_note(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-uji',
            'role' => 'Admin',
        ]);
        $loan = $this->pendingLoan();

        $this->actingAs($admin)
            ->patch(route('loans.approve', $loan), [
                'catatan_admin' => 'Silakan ambil di ruang alat.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('peminjamans', [
            'id' => $loan->id,
            'status' => 'Disetujui oleh Admin',
            'catatan_admin' => 'Silakan ambil di ruang alat.',
        ]);
    }

    public function test_non_admin_cannot_approve_a_pending_loan(): void
    {
        $student = User::factory()->create([
            'username' => 'siswa-uji',
            'role' => 'Siswa',
        ]);
        $loan = $this->pendingLoan();

        $this->actingAs($student)
            ->patch(route('loans.approve', $loan))
            ->assertForbidden();

        $this->assertDatabaseHas('peminjamans', [
            'id' => $loan->id,
            'status' => 'Menunggu Verifikasi',
        ]);
    }

    private function pendingLoan(): Peminjaman
    {
        return Peminjaman::create([
            'nama_alat' => 'Laptop',
            'borrower' => 'siswa-peminjam',
            'tanggal_pinjam' => '2026-11-20',
            'status' => 'Menunggu Verifikasi',
        ]);
    }
}
