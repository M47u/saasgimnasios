<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaasLoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('saas')->check()) {
            return redirect()->route('saas.dashboard');
        }
        return view('saas.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('saas')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('saas.dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('saas')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('saas.login');
    }
}
