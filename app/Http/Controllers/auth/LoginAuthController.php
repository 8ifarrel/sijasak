<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAuthController extends Controller
{
    public function index()
    {
        return view('auth.pages.login.index', [
            'meta_description' => 'Login Admin Sijasak',
            'page_title' => 'Login Admin',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->has('remember-me');

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard.index')
                ->with('success', 'Selamat datang di Admin Panel Sijasak, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username', 'remember-me'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login.index');
    }
}