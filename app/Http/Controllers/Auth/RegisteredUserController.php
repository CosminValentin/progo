<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class RegisteredUserController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            return redirect()->to(Route::has('home') ? route('home') : url('/home'));
        }
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required','string','max:120','unique:users,name'],
            'email'           => ['required','string','email:rfc,dns','max:191','unique:users,email'],
            'password' => ['required', 'string'],
            'dni'             => ['nullable','string','max:20','unique:users,dni'],
            'first_name'      => ['nullable','string','max:191'],
            'last_name1'      => ['nullable','string','max:191'],
            'last_name2'      => ['nullable','string','max:191'],
            'birth_date'      => ['nullable','date'],
            'gender'          => ['nullable', Rule::in(['Varon','Mujer','Otro','Prefiero no decirlo'])],
            'education_level' => ['nullable', Rule::in([
                'Sin Estudios','Primaria','ESO','Bachillerato','FP Básica','FP Media',
                'FP Superior','Grado','Máster','Doctorado'
            ])],
            'eu_resident'     => ['nullable','boolean'],
        ]);

        $user = User::create([
            'name'            => $data['name'],
            'email'           => $data['email'],
            'password'        => Hash::make($data['password']),
            'dni'             => $data['dni']             ?? null,
            'first_name'      => $data['first_name']      ?? null,
            'last_name1'      => $data['last_name1']      ?? null,
            'last_name2'      => $data['last_name2']      ?? null,
            'birth_date'      => $data['birth_date']      ?? null,
            'gender'          => $data['gender']          ?? null,
            'education_level' => $data['education_level'] ?? null,
            'eu_resident'     => (bool)($data['eu_resident'] ?? false),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->to(Route::has('home') ? route('home') : url('/home'))
            ->with('success', 'Usuario registrado correctamente.');
    }
}
