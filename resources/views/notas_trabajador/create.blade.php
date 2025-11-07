@if(request()->boolean('modal'))

  <form action="{{ route('notas.store') }}" method="POST"
        class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 sm:p-8 shadow">
    @csrf
    @include('components.modal-hidden') {{-- <- importante --}}
    @include('notas_trabajador._form', [
      'nota' => null,
      'participants' => $participants,
      'participant'  => $participant ?? null,
      'isModal' => true
    ])
  </form>

@else

@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Nueva nota</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Crea una nota interna para un participante.</p>
    </div>
    <a href="{{ route('notas.index') }}"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 transition">
      <i class="fa-solid fa-arrow-left"></i> Volver
    </a>
  </div>
@endsection

@section('content')
  <form action="{{ route('notas.store') }}" method="POST"
        class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 shadow-lg">
    @csrf
    @include('notas_trabajador._form', [
      'nota' => null,
      'participants' => $participants,
      'participant'  => $participant ?? null,
      'isModal' => false
    ])
  </form>
@endsection

@endif
