<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index($tenant) { return view('owner.settings.index', compact('tenant')); }
    public function update(Request $request, $tenant) { return redirect()->back(); }
}
