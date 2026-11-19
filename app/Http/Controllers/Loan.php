<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'alat' => 'required|string',
            'borrower' => 'required|string',
            'tanggal_pinjam' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'foto_url' => 'nullable|url',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('loans', 'public');
        } elseif ($request->foto_url) {
            $fotoPath = $request->foto_url;
        }

        Loan::create([
            'user_id' => Auth::id(),
            'alat' => $request->alat,
            'borrower' => $request->borrower,
            'foto' => $fotoPath,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'Dipinjam',
        ]);

        return back()->with('success', 'Peminjaman alat berhasil diajukan.');
    }

    public function returnLoan(Loan $loan) {
        if (Auth::user()->role !== 'Admin') {
            abort(403);
        }
        $loan->update(['status' => 'Dikembalikan']);
        return back()->with('success', 'Status peminjaman diperbarui.');
    }

    public function destroy(Loan $loan) {
        if (Auth::user()->role !== 'Admin') {
            abort(403);
        }
        $loan->delete();
        return back()->with('success', 'Data peminjaman berhasil dihapus.');
    }
}