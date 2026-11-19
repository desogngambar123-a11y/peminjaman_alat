<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Support\EquipmentCatalog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $section = $request->input('section', $request->input('tab') === 'riwayat' ? 'riwayat' : 'dashboard');
        if (!in_array($section, ['dashboard', 'alat', 'pengajuan', 'riwayat'], true)) {
            $section = 'dashboard';
        }

        $loans = Peminjaman::with('feedbacks')
            ->when($request->user()->role !== 'Admin', function ($query) use ($request) {
                $query->where('borrower', $request->user()->username);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('nama_alat', 'like', "%{$search}%")
                        ->orWhere('borrower', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        if ($section === 'pengajuan') {
            $loans = $loans->filter(fn ($loan) => $loan->status === 'Menunggu Verifikasi');
        } elseif ($section === 'riwayat') {
            $loans = $loans->filter(fn ($loan) => in_array($loan->status, ['Dikembalikan', 'Terlambat', 'Ditolak'], true));
        } elseif ($section === 'dashboard') {
            $loans = $loans->filter(fn ($loan) => in_array($loan->status, ['Dipinjam', 'Disetujui oleh Admin', 'Terlambat'], true));
        }

        $statsQuery = Peminjaman::query();
        if ($request->user()->role !== 'Admin') {
            $statsQuery->where('borrower', $request->user()->username);
        }

        $statMenunggu = (clone $statsQuery)->where('status', 'Menunggu Verifikasi')->count();
        $statDipinjam = (clone $statsQuery)->whereIn('status', ['Dipinjam', 'Disetujui oleh Admin'])->count();
        $statDikembalikan = (clone $statsQuery)->where('status', 'Dikembalikan')->count();
        $statTerlambat = (clone $statsQuery)->where('status', 'Terlambat')->count();
        $totalPeminjaman = (clone $statsQuery)->count();
        $daftarAlat = Peminjaman::query()
            ->whereNotNull('nama_alat')
            ->where('nama_alat', '<>', '')
            ->distinct()
            ->orderBy('nama_alat')
            ->pluck('nama_alat');
        $katalogAlat = EquipmentCatalog::options();
        $equipmentList = collect($katalogAlat)
            ->flatMap(fn ($items, $category) => collect($items)->map(fn ($name) => [
                'name' => $name,
                'category' => $category,
            ]))
            ->concat($daftarAlat->diff(collect(EquipmentCatalog::names()))->map(fn ($name) => [
                'name' => $name,
                'category' => 'Alat lainnya',
            ]))
            ->values();
        $activeLoanCounts = Peminjaman::query()
            ->selectRaw('nama_alat, COUNT(*) AS active_count')
            ->whereIn('status', ['Dipinjam', 'Disetujui oleh Admin', 'Terlambat'])
            ->groupBy('nama_alat')
            ->pluck('active_count', 'nama_alat');
        $equipmentPhotos = Peminjaman::query()
            ->whereNotNull('foto_alat')
            ->orderByDesc('id')
            ->get(['nama_alat', 'foto_alat'])
            ->unique('nama_alat')
            ->keyBy('nama_alat');

        return view('dashboard', compact(
            'loans',
            'statMenunggu',
            'statDipinjam',
            'statDikembalikan',
            'statTerlambat',
            'totalPeminjaman',
            'daftarAlat',
            'katalogAlat',
            'equipmentList',
            'activeLoanCounts',
            'equipmentPhotos',
            'search',
            'section'
        ));
    }
}
