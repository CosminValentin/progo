@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400 flex items-center gap-2">
        <i class="fa-solid fa-handshake text-indigo-500"></i>
        Nuevo convenio
      </h1>
      <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
        Crea un convenio y vincúlalo a una empresa o documento existente.
      </p>
    </div>

    <a href="{{ route('agreements.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 
              bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-sm font-medium shadow-sm transition">
      <i class="fa-solid fa-arrow-left text-gray-500 dark:text-slate-400"></i>
      Volver
    </a>
  </div>
@endsection

@section('content')
  {{-- Mensajes de error global --}}
  @if ($errors->any())
    <div class="mb-6 rounded-xl border border-rose-300 bg-rose-50 dark:border-rose-600 dark:bg-rose-900/40 
                text-rose-800 dark:text-rose-100 p-4 shadow-sm flex gap-3">
      <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg mt-0.5"></i>
      <div>
        <strong class="block font-semibold mb-1">Corrige los errores:</strong>
        <ul class="text-sm list-disc list-inside space-y-0.5">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  @endif

  <form method="POST" action="{{ route('agreements.store') }}"
        class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 p-6 md:p-8 shadow-lg backdrop-blur-sm space-y-8 transition">
    @csrf

    {{-- Encabezado visual del formulario --}}
    <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-700 pb-3 mb-2">
      <div class="flex items-center gap-3">
        <div class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 p-2 rounded-lg">
          <i class="fa-solid fa-file-signature text-xl"></i>
        </div>
        <div>
          <h2 class="font-semibold text-lg text-gray-800 dark:text-slate-200">Detalles del convenio</h2>
          <p class="text-sm text-gray-500 dark:text-slate-400">Completa los datos requeridos antes de guardar.</p>
        </div>
      </div>
    </div>

    {{-- Formulario principal --}}
    @include('agreements._form', ['companies' => $companies, 'documents' => $documents])

    {{-- Acciones inferiores --}}
    <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-end gap-3">
      <a href="{{ route('agreements.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 
                bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 
                text-gray-700 dark:text-slate-200 text-sm font-medium shadow-sm transition">
        <i class="fa-solid fa-ban text-gray-400 dark:text-slate-400"></i>
        Cancelar
      </a>

      <button type="submit"
              class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg 
                     bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 
                     text-white text-sm font-semibold shadow-md transition focus:ring-2 focus:ring-indigo-400 focus:outline-none">
        <i class="fa-solid fa-floppy-disk"></i>
        Guardar convenio
      </button>
    </div>
  </form>
@endsection
