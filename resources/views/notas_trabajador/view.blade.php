@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-3xl font-semibold text-indigo-700 dark:text-indigo-300">Nota de Trabajador #{{ $nota->id_nota }}</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">
        {{ $nota->participant->nombre ?? 'Participante no asignado' }}
      </p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('notas.edit', $nota) }}"
         class="px-3 py-1.5 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 transition text-sm">
        Editar
      </a>
      <form action="{{ route('notas.destroy', $nota) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres borrar esta nota?');">
        @csrf
        @method('DELETE')
        <button type="submit"
          class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition text-sm">
          Borrar
        </button>
      </form>
      <a href="{{ route('notas.index') }}"
         class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition text-sm">
         Volver
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
    <div class="rounded-3xl border bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-xl font-semibold mb-4 text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
        <i class="fas fa-user text-indigo-600 dark:text-indigo-300"></i>
        Participante
      </h2>
      <p class="text-gray-700 dark:text-slate-300 text-lg font-medium">{{ $nota->participant->nombre ?? '—' }}</p>
    </div>

    <div class="rounded-3xl border bg-gradient-to-br from-green-50 to-green-100 p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-xl font-semibold mb-4 text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
        <i class="fas fa-calendar-alt text-green-600 dark:text-green-300"></i>
        Fecha y hora
      </h2>
      <time datetime="{{ $nota->fecha_hora }}" class="text-gray-700 dark:text-slate-400 text-lg font-medium">
        {{ optional($nota->fecha_hora)->format('d/m/Y H:i') ?? '—' }}
      </time>
    </div>

    <div class="md:col-span-2 rounded-3xl border bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-xl font-semibold mb-4 text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
        <i class="fas fa-sticky-note text-yellow-600 dark:text-yellow-300"></i>
        Texto de la Nota
      </h2>
      <div class="prose max-w-none text-sm dark:prose-invert whitespace-pre-line leading-relaxed text-gray-700 dark:text-slate-300">
        {!! nl2br(e($nota->texto)) !!}
      </div>
    </div>
  </div>
@endsection
