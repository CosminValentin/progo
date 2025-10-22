@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800 dark:text-slate-100">Nueva empresa</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">Completa los datos y guarda para dar de alta.</p>
    </div>
    <a href="{{ route('companies') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      <i class="fa-solid fa-arrow-left mr-2"></i> Volver
    </a>
  </div>
@endsection

@section('content')
  @if ($errors->any())
    <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 p-4 text-rose-800 shadow-sm dark:border-rose-700 dark:bg-rose-900 dark:text-rose-200">
      <strong class="block mb-2 text-sm font-medium">Corrige los errores</strong>
      <ul class="text-sm list-disc pl-5">
        @foreach ($errors->all() as $e) 
          <li>{{ $e }}</li> 
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('savecompany') }}" class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-md space-y-4">
    @csrf

    @include('companies._form')

    <div class="flex items-center justify-end gap-3 mt-4">
      <a href="{{ route('companies') }}" class="inline-flex items-center px-4 py-2 text-sm rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <i class="fa-solid fa-times mr-2"></i> Cancelar
      </a>
      <button type="submit" class="inline-flex items-center px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-md">
        <i class="fa-solid fa-save mr-2"></i> Guardar
      </button>
    </div>
  </form>
@endsection
  