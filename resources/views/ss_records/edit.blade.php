@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Editar Registro SS</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Modifica la información del registro.</p>
    </div>
    <a href="{{ route('ss.index') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 transition">
      <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
  </div>
@endsection

@section('content')
  @if ($errors->any())
    <div class="mb-6 rounded-xl border border-rose-200/80 bg-rose-50 p-4 text-rose-800 shadow-sm dark:border-rose-700/40 dark:bg-rose-900/30 dark:text-rose-200">
      <strong class="block mb-1">Corrige los errores</strong>
      <ul class="text-sm list-disc list-inside">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('ss.update', $ss) }}"
        class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow space-y-6">
    @method('PUT')
    @include('ss_records._form', ['ss' => $ss, 'participants' => $participants])
  </form>
@endsection
