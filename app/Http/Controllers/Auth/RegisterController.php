<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Tampilkan Daftar User (Index)
     */
    public function index(Request $request)
{
    $search = $request->get('search');

    $users = User::with('santri')
        ->when($search, function($query) use ($search) {
            return $query->where('username', 'like', "%{$search}%")
                         ->orWhere('role', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(15) // Ubah get() jadi paginate(15)
        ->appends(['search' => $search]); // Jaga parameter search

    return view('admin.registrasi.index', compact('users', 'search'));
}

    /**
     * Tampilkan Form Tambah User (Create)
     */
    public function create()
    {
        $santri = Santri::all();
        return view('admin.registrasi.create', compact('santri'));
    }

    /**
     * Simpan User Baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,user,bendahara,keamanan',
            'nis_FK'   => 'nullable|exists:santri,nis',
        ]);

        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'nis_FK'   => $request->nis_FK,
        ]);

        return redirect()->route('admin.registrasi.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Hapus User
     */
    public function destroy($id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete();

        return redirect()->route('admin.registrasi.index')
            ->with('success', 'User berhasil dihapus.');
    }
}