<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller {
    public function store(Request $request) {
        $request->validate(['text' => 'required|string']);

        Feedback::create([
            'user_id' => Auth::id(),
            'text' => $request->text,
        ]);

        return back()->with('success', 'Feedback dikirim.');
    }
}