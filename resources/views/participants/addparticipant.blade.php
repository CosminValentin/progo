@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400">Nuevo participante</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Completa los datos y guarda para dar de alta.</p>
    </div>
    <a href="{{ route('participants') }}"
       class="px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
      Volver
    </a>
  </div>
@endsection

@section('content')
  {{-- Errores de validación --}}
  @if ($errors->any())
    <div class="mb-6 rounded-xl border border-rose-200/80 bg-rose-50 p-4 text-rose-800 shadow-sm dark:border-rose-700/40 dark:bg-rose-900/30 dark:text-rose-200">
      <strong class="block mb-1">Corrige los errores</strong>
      <ul class="list-disc list-inside text-sm">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Formulario de nuevo participante --}}
  <form method="POST" action="{{ route('saveparticipant') }}"
        class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow space-y-6">
    @csrf

    {{-- Incluye los campos del formulario --}}
    @include('participants._form')

    {{-- Botones --}}
    <div class="flex justify-end gap-2">
      <a href="{{ route('participants') }}"
         class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
        Cancelar
      </a>
      <button type="submit"
              class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow transition">
        Guardar
      </button>
    </div>
  </form>
@endsection
