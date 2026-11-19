<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request, Peminjaman $loan)
    {
        $user = $request->user();
        if ($user->role !== 'Admin' && $loan->borrower !== $user->username) {
            abort(403);
        }

        $validated = $request->validate([
            'text' => ['required', 'string', 'max:1000'],
        ]);

        Feedback::create([
            'loan_id' => $loan->id,
            'user' => $user->username,
            'role' => $user->role,
            'pesan' => $validated['text'],
        ]);

        return back()->with('success', $user->role === 'Admin'
            ? 'Tanggapan admin berhasil dikirim.'
            : 'Feedback berhasil dikirim.');
    }
}
