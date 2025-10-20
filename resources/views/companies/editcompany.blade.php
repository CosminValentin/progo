@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-2xl font-bold">Editar empresa</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">Actualiza la información de <strong>{{ $company->nombre }}</strong>.</p>
    </div>
  </div>
@endsection

@section('content')
  <div x-data="{open:false}">
    @if ($errors->any())
      <div class="mb-6 rounded-xl border border-rose-200/80 bg-rose-50 p-4 text-rose-800 shadow-sm dark:border-rose-700/40 dark:bg-rose-900/30 dark:text-rose-200">
        <strong class="block mb-1">Corrige los errores</strong>
        <ul class="text-sm list-disc list-inside">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('updatecompany', $company) }}"
          class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow space-y-6">
      @csrf
      @include('companies._form', ['company' => $company])

      <div class="flex items-center justify-between pt-2">
        <a href="{{ route('companies') }}" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">Volver</a>

        <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow">Guardar cambios</button>
      </div>
    </form>


@endsection
