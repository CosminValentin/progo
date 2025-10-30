<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            return redirect()->to(Route::has('home') ? route('home') : url('/home'));
        }
        return view('auth.register');
    }

    // Procesar registro
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:120','unique:users,name'],
            'password' => ['required', Password::min(6)->letters()->numbers()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['name'].'@fake.local',
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->to(Route::has('home') ? route('home') : url('/home'))
            ->with('success', 'Usuario registrado correctamente.');
    }
}
