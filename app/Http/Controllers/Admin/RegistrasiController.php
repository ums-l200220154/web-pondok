<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Santri;
use Illuminate\Http\Request;

class RegistrasiController extends Controller
{
    public function index(Request $request)
{
    $search = $request->get('search');

    // Ambil user dengan relasi santri + filter pencarian
    $users = User::with('santri')
        ->when($search, function($query) use ($search) {
            return $query->where('username', 'like', "%{$search}%")
                         ->orWhere('role', 'like', "%{$search}%");
        })
        ->get();

    // Ambil semua santri (tetap dikirim jika dibutuhkan untuk modal/dropdown)
    $santri = Santri::all();

    return view('admin.registrasi.index', compact('users', 'santri', 'search'));
}
}
