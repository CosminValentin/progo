@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Subir CV</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Selecciona el archivo y el participante.</p>
    </div>
    <a href="{{ route('cvs.index') }}"
       class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">
      Volver
    </a>
  </div>
@endsection

@section('content')
  @if(session('error'))
    <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 p-4 text-rose-800 dark:border-rose-600 dark:bg-rose-900 dark:text-rose-100 shadow">
      {{ session('error') }}
    </div>
  @endif
  @if ($errors->any())
    <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 p-4 text-rose-800 dark:border-rose-600 dark:bg-rose-900 dark:text-rose-100 shadow">
      <strong>Corrige los errores:</strong>
      <ul class="list-disc list-inside text-sm mt-1">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('cvs.store') }}" enctype="multipart/form-data"
        class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow p-6 space-y-8">
    @csrf

    {{-- Archivo --}}
    <section>
      <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">Archivo (PDF o imagen)</label>
      <input type="file" name="file" accept=".pdf,image/*"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      <p class="text-xs text-gray-400 mt-1">Tamaño máx. 20MB.</p>
      @error('file') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
    </section>

    {{-- Participante (owner) --}}
    <section>
      @include('cv._owner_selector', ['participants' => $participants])
    </section>

    <section>
      <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="protegido" value="1" class="rounded border-gray-300 dark:border-slate-600">
        <span class="text-sm">Marcar como protegido (no se podrá eliminar)</span>
      </label>
    </section>

    <div class="flex justify-end gap-2">
      <a href="{{ route('cvs.index') }}"
         class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">
        Cancelar
      </a>
      <button class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow">
        Subir CV
      </button>
    </div>
  </form>
@endsection
