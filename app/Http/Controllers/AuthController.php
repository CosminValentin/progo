<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Mostrar la página de login
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    // Procesar el login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string'], // nombre o email
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['login'])
                    ->orWhere('name', $credentials['login'])
                    ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'login' => 'Credenciales no válidas.',
            ])->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    // Mostrar el formulario de registro
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    // Procesar el registro
    public function register(Request $request)
    {
        // Validación de los datos del formulario sin la confirmación de contraseña
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string'], // Ya no tiene "confirmed"
        ]);

        // Crear el usuario con los datos validados
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']), // Hasheamos la contraseña antes de guardarla
        ]);

        // Autenticar al usuario
        Auth::login($user);
        $request->session()->regenerate();

        // Redirigir al home directamente después del registro
        return redirect()->route('home');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Sesión cerrada correctamente.');
    }
}
