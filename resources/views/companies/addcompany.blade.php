@extends('layouts.app_windmill')

@section('header')
  <h1 class="text-2xl font-bold">Nueva empresa</h1>
@endsection

@section('content')
  @if ($errors->any())
    <div class="mb-6 rounded-xl border border-rose-200/80 bg-rose-50 p-4 text-rose-800 shadow-sm">
      <div class="text-sm font-semibold mb-1">Corrige los errores</div>
      <ul class="text-sm list-disc list-inside">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('savecompany') }}" class="rounded-2xl border border-gray-200/80 bg-white dark:bg-slate-800 p-6 shadow-lg space-y-6">
    @csrf

    @include('companies._form')

    <div class="flex items-center justify-end gap-3 pt-2">
      <a href="{{ route('companies') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 shadow-sm transition">Cancelar</a>
      <button class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold shadow hover:shadow-lg hover:scale-[1.02] active:scale-100 transition-all">
        Guardar
      </button>
    </div>
  </form>
@endsection
