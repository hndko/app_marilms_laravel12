<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function index($tenant) { return view('owner.tokens.index', compact('tenant')); }
    public function purchase(Request $request, $tenant) { return redirect()->back(); }
}
