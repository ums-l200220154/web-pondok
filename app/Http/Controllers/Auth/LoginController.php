<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
{
    // Kita gunakan nama input 'login' untuk menampung email/username
    $request->validate([
        'login' => 'required|string',
        'password' => 'required|string',
    ]);

    // Tentukan apakah input 'login' adalah email atau username
    $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // Cari user berdasarkan field tersebut
    $user = User::where($fieldType, $request->login)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'login' => 'Username/Email atau password salah.',
        ])->withInput();
    }

    Auth::login($user);
    $request->session()->regenerate();

    // Redirect berdasarkan role
    return $this->redirectUser($user);
}

private function redirectUser($user)
{
    switch ($user->role) {
        case 'admin': return redirect()->route('admin.dashboard');
        case 'bendahara': return redirect()->route('bendahara.dashboard');
        case 'keamanan': return redirect()->route('keamanan.dashboard');
        case 'user': return redirect()->route('user.dashboard');
        default:
            Auth::logout();
            return redirect()->route('home')->withErrors(['login' => 'Role tidak dikenali.']);
    }
}
    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
