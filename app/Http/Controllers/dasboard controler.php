<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::when($search, function ($query, $search) {
            return $query->where('nama_alat', 'like', "%{$search}%")
                         ->orWhere('borrower', 'like', "%{$search}%");
        })->latest()->get();

        $feedbacks = Feedback::latest()->get();

        // Statistik
        $totalAlat = 362; // Stok master
        $totalDipinjam = Peminjaman::where('status', 'Dipinjam')->count();
        $totalKembaliHariIni = Peminjaman::where('status', 'Dikembalikan')->whereDate('updated_at', now())->count();
        $totalTerlambat = Peminjaman::where('status', 'Terlambat')->count();

        return view('dashboard', compact(
            'peminjamans', 'feedbacks', 'totalAlat', 
            'totalDipinjam', 'totalKembaliHariIni', 'totalTerlambat'
        ));
    }

    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required',
            'borrower' => 'required',
            'tanggal_pinjam' => 'required|date',
            'foto_alat' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_alat')) {
            $fotoPath = $request->file('foto_alat')->store('alat', 'public');
        }

        Peminjaman::create([
            'nama_alat' => $request->nama_alat,
            'borrower' => $request->borrower,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'foto_alat' => $fotoPath,
            'status' => 'Dipinjam',
        ]);

        return redirect()->back()->with('success', 'Data peminjaman berhasil ditambahkan.');
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'Dikembalikan']);

        return redirect()->back()->with('success', 'Status berhasil diubah menjadi Dikembalikan.');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->foto_alat) {
            Storage::disk('public')->delete($peminjaman->foto_alat);
        }
        $peminjaman->delete();

        return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function storeFeedback(Request $request)
    {
        $request->validate(['pesan' => 'required']);

        Feedback::create([
            'user' => auth()->user()->name ?? 'Pengguna',
            'pesan' => $request->pesan,
        ]);

        return redirect()->back()->with('success', 'Tanggapan/Feedback terkirim.');
    }
}