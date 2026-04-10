<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index() {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role == 'admin') return redirect()->route('admin.dashboard');
        if ($user->role == 'bendahara') return redirect()->route('bendahara.dashboard');
        if ($user->role == 'keamanan') return redirect()->route('keamanan.dashboard');
        return redirect()->route('user.dashboard');
    }

    $content = \App\Models\SiteContent::pluck('value', 'key');
    return view('home', compact('content'));
}
}
