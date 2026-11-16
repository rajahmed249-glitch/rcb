<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlayerApplication;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        return view('admin.applications.index', [
            'applications' => PlayerApplication::orderByDesc('created_at')->get(),
        ]);
    }

    public function destroy(PlayerApplication $application)
    {
        $application->delete();

        return redirect()->route('admin.applications.index')->with('success', __('site.admin.deleted'));
    }
}
