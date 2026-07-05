<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index($tenant) { return view('owner.participants.index', compact('tenant')); }
    public function create($tenant) { return view('owner.participants.create', compact('tenant')); }
    public function store(Request $request, $tenant) { return redirect()->back(); }
    public function show($tenant, $participant) { return view('owner.participants.show', compact('tenant', 'participant')); }
    public function edit($tenant, $participant) { return view('owner.participants.edit', compact('tenant', 'participant')); }
    public function update(Request $request, $tenant, $participant) { return redirect()->back(); }
    public function destroy($tenant, $participant) { return redirect()->back(); }
    public function import(Request $request, $tenant) { return redirect()->back(); }
    public function resetPassword($tenant, $participant) { return redirect()->back(); }
}
