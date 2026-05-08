<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\GymUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Buscar todos los usuarios con este email
        $usuarios = GymUser::where('email', $validated['email'])
            ->where('activo', true)
            ->get();

        // Verificar que existe al menos un usuario con este email y la contraseña es correcta
        $usuarioValido = $usuarios->first(
            fn($u) => Hash::check($validated['password'], $u->password)
        );

        if (!$usuarioValido) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email o contraseña incorrectos.']);
        }

        // Si hay un solo usuario con este email, ingresar directamente
        if ($usuarios->count() === 1) {
            Auth::guard('gym')->login($usuarioValido);
            $request->session()->regenerate();
            return $this->redirectAfterLogin($usuarioValido);
        }

        // Si hay múltiples usuarios con este email, mostrar selector de gimnasios
        $gimnasios = $usuarios->map(fn($u) => $u->gimnasio)->unique('id');
        return view('gym.login-select-gym', [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'gimnasios' => $gimnasios,
        ]);
    }

    public function loginSelectGym(Request $request)
    {
        $validated = $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required'],
            'gimnasio_id' => ['required', 'exists:gimnasios,id'],
        ]);

        // Buscar el usuario específico con este email + gimnasio
        $user = GymUser::where('email', $validated['email'])
            ->where('gimnasio_id', $validated['gimnasio_id'])
            ->where('activo', true)
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()
                ->withInput($request->only('email', 'gimnasio_id'))
                ->withErrors(['email' => 'Credenciales incorrectas.']);
        }

        Auth::guard('gym')->login($user);
        $request->session()->regenerate();
        return $this->redirectAfterLogin($user);
    }

    public function logout(Request $request)
    {
        Auth::guard('gym')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('gym.login');
    }

    private function redirectAfterLogin(\App\Models\GymUser $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->must_change_password) {
            return redirect()->route('gym.password.change')
                ->with('warning', 'Debes cambiar tu contraseña antes de continuar.');
        }

        return redirect()->intended(route('gym.dashboard'));
    }
}
