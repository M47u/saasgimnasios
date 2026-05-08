<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class GymPasswordController extends Controller
{
    public function show()
    {
        return view('gym.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'password.required'         => 'La nueva contraseña es obligatoria.',
            'password.confirmed'        => 'La confirmación de contraseña no coincide.',
            'password.min'              => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        /** @var \App\Models\GymUser $user */
        $user = Auth::guard('gym')->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        $user->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        $request->session()->regenerate();

        return redirect()->route('gym.dashboard')
            ->with('success', 'Contraseña actualizada correctamente.');
    }
}
