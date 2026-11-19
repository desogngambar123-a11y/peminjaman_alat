<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Support\EquipmentCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoanController extends Controller
{
    public function store(Request $request)
    {
        $daftarAlat = Peminjaman::query()
            ->whereNotNull('nama_alat')
            ->where('nama_alat', '<>', '')
            ->distinct()
            ->pluck('nama_alat')
            ->all();

        $request->validate([
            'alat' => ['required', 'string', Rule::in([...EquipmentCatalog::names(), ...$daftarAlat, '__baru__'])],
            'alat_baru' => 'required_if:alat,__baru__|nullable|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_url' => 'nullable|url|max:255',
        ]);

        $namaAlat = $request->alat === '__baru__' ? $request->alat_baru : $request->alat;

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('loans', 'public');
        } elseif ($request->foto_url) {
            $fotoPath = $request->foto_url;
        }

        Peminjaman::create([
            'nama_alat' => $namaAlat,
            'borrower' => $request->user()->username,
            'foto_alat' => $fotoPath,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'Menunggu Verifikasi',
        ]);

        return back()->with('success', 'Pengajuan peminjaman berhasil dikirim dan menunggu verifikasi Admin.');
    }

    public function approve(Request $request, Peminjaman $loan)
    {
        $this->ensureAdmin();

        if ($loan->status !== 'Menunggu Verifikasi') {
            return back()->withErrors(['loan' => 'Pengajuan ini sudah diproses.']);
        }

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $loan->update([
            'status' => 'Disetujui oleh Admin',
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return back()->with('success', 'Pengajuan disetujui oleh Admin.');
    }

    public function reject(Request $request, Peminjaman $loan)
    {
        $this->ensureAdmin();

        if ($loan->status !== 'Menunggu Verifikasi') {
            return back()->withErrors(['loan' => 'Pengajuan ini sudah diproses.']);
        }

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $loan->update([
            'status' => 'Ditolak',
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    public function returnLoan(Peminjaman $loan)
    {
        $this->ensureAdmin();

        if (!in_array($loan->status, ['Dipinjam', 'Disetujui oleh Admin', 'Terlambat'], true)) {
            return back()->withErrors(['loan' => 'Hanya peminjaman yang sudah disetujui yang dapat dikembalikan.']);
        }

        $loan->update(['status' => 'Dikembalikan']);

        return back()->with('success', 'Status peminjaman diperbarui.');
    }

    public function destroy(Peminjaman $loan)
    {
        $this->ensureAdmin();

        $loan->delete();

        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    private function ensureAdmin(): void
    {
        abort_unless(Auth::user()->role === 'Admin', 403);
    }
}
