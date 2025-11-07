@extends('layouts.auth')
@section('title','Panel · PRO-GO')

@section('content')
<div class="min-h-dvh flex items-center justify-center px-4 py-10">
  <div class="w-full max-w-3xl">
    <div class="bg-white rounded-3xl shadow-xl ring-1 ring-black/5 p-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold tracking-tight">Panel de control</h1>
          <p class="text-gray-600 mt-1">Bienvenido, {{ auth()->user()->name }}.</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="px-4 py-2 rounded-2xl bg-gray-100 hover:bg-gray-200 transition">Cerrar sesión</button>
        </form>
      </div>
<form action="{{ route('logout') }}" method="POST" class="ml-2">
    @csrf
    <button
      class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-sm font-medium transition"
      type="submit">
      🚪 {{ __('messages.logout') }}
    </button>
</form>

      <div class="mt-8 grid sm:grid-cols-2 gap-6">
        <a href="{{ url('companies') }}" class="block rounded-2xl p-6 border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50 transition">
          <div class="text-lg font-semibold">Empresas</div>
          <p class="text-sm text-gray-600 mt-1">Ir al módulo de empresas</p>
        </a>
        {{-- Añade más tarjetas a tus módulos --}}
      </div>
    </div>
  </div>
</div>
@endsection
