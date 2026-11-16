<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;

class SponsorController extends Controller
{
    public function index()
    {
        return view('sponsors.index', [
            'sponsors' => Sponsor::orderBy('sort_order')->get(),
        ]);
    }
}
