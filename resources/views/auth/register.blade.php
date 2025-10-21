@extends('layouts.auth')

@section('title', 'Registrar · PRO-GO')

@section('content')
<div class="min-h-dvh flex items-center justify-center px-4 py-10">
  <div class="w-full max-w-md">
    {{-- Card --}}
    <div class="bg-white/80 backdrop-blur rounded-3xl shadow-xl ring-1 ring-black/5 overflow-hidden">
      {{-- Header --}}
      <div class="px-8 pt-8 pb-6 bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 text-white">
        <h1 class="text-2xl font-bold tracking-tight">Bienvenido a PRO-GO</h1>
        <p class="text-white/90 mt-1">Crea tu cuenta para comenzar</p>
      </div>

      {{-- Body --}}
      <div class="px-8 py-6">
        @if(session('status'))
          <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3">
            {{ session('status') }}
          </div>
        @endif

        @if($errors->any())
          <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3">
            {{ $errors->first() }}
          </div>
        @endif

<form action="{{ route('register') }}" method="POST" class="space-y-5">
  @csrf

  <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de usuario</label>
    <input type="text" name="name" value="{{ old('name') }}" required autofocus
           class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 outline-none focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 transition" />
  </div>

  <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
    <input type="email" name="email" value="{{ old('email') }}" required
           class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 outline-none focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 transition" />
  </div>

  <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
    <input type="password" name="password" required
           class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 outline-none focus:bg-white focus:border-brand focus:ring-2 focus:ring-brand/20 transition" />
  </div>

  <button class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 rounded-2xl bg-brand text-dark font-semibold shadow hover:brightness-105 active:scale-[0.99] transition">
    Crear cuenta
  </button>
</form>
        <p class="text-center text-sm text-gray-600 mt-6">
          ¿Ya tienes cuenta?
          <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Iniciar sesión</a>
        </p>
      </div>
    </div>

    {{-- Footer --}}
    <p class="text-center text-xs text-gray-500 mt-6">
      © {{ date('Y') }} PRO-GO · Seguridad por sesión y CSRF activas
    </p>
  </div>
</div>
@endsection


