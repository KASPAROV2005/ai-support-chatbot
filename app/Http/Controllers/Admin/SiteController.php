<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function index()
    {
        $sites = Site::latest()->get();
        return view('admin.sites', compact('sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
        ]);

        Site::create([
            'name' => $request->name,
            'domain' => $request->domain ?: null,
            'site_key' => Str::random(40),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Site created successfully.');
    }
}
