{{-- resources/views/contracts/create.blade.php --}}
@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Nuevo contrato</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Completa los datos y guarda.</p>
    </div>
    <a href="{{ route('contracts.index') }}"
       class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">
      Volver
    </a>
  </div>
@endsection

@section('content')
  @if ($errors->any())
    <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 p-4 text-rose-800 dark:border-rose-600 dark:bg-rose-900 dark:text-rose-100 shadow">
      <strong>Corrige los errores:</strong>
      <ul class="list-disc list-inside text-sm mt-1">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('contracts.store') }}"
        class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow space-y-6">
    @csrf
    @include('contracts._form', compact('participants','companies','offers','documents'))

    <div class="flex justify-end gap-2">
      <a href="{{ route('contracts.index') }}"
         class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">
        Cancelar
      </a>
      <button class="px-5 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow">
        Guardar
      </button>
    </div>
  </form>
@endsection
