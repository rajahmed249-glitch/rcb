<?php

namespace App\Http\Controllers;

use App\Models\PlayerApplication;
use Illuminate\Http\Request;

class PlayerApplicationController extends Controller
{
    public function create()
    {
        return view('be-a-player.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'age' => ['required', 'integer', 'min:10', 'max:60'],
            'preferred_role' => ['nullable', 'string', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        PlayerApplication::create($validated);

        return redirect()
            ->route('be-a-player.create')
            ->with('success', 'Thank you! Your application has been received. Our team will contact you soon. 🏏');
    }
}
