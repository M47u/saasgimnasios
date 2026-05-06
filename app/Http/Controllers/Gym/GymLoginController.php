<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GymLoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('gym')->check()) {
            return redirect()->route('gym.dashboard');
        }
        return view('gym.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('gym')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('gym.dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('gym')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('gym.login');
    }
}
