@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Registro SS #{{ $ss->id }}</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">
        {{ $ss->participant->nombre ?? '—' }} {{ $ss->participant?->dni_nie ? '(' . $ss->participant->dni_nie . ')' : '' }}
      </p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('ss.edit', $ss) }}" class="px-3 py-2 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300">
        Editar
      </a>
      <a href="{{ route('ss.index') }}" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800">
        Volver
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
    <div class="rounded-3xl border bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-xl font-semibold mb-4 text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
        <i class="fas fa-user"></i> Participante
      </h2>
      <p class="text-gray-700 dark:text-slate-300 text-lg font-medium">
        {{ $ss->participant->nombre ?? '—' }}
      </p>
      <p class="text-gray-500 dark:text-slate-400 text-sm">
        {{ $ss->participant?->dni_nie }}
      </p>
    </div>

    <div class="rounded-3xl border bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-xl font-semibold mb-4 text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
        <i class="fas fa-clipboard-check"></i> Resumen
      </h2>
      <dl class="text-gray-700 dark:text-slate-300 space-y-2">
        <div><dt class="font-semibold">Régimen:</dt><dd>{{ $ss->regimen }}</dd></div>
        <div><dt class="font-semibold">Días en alta:</dt><dd>{{ $ss->dias_alta ?? '—' }}</dd></div>
        <div><dt class="font-semibold">Jornadas reales:</dt><dd>{{ $ss->jornadas_reales ?? '—' }}</dd></div>
        <div><dt class="font-semibold">Coeficiente:</dt><dd>{{ $ss->coef_aplicado ?? '—' }}</dd></div>
        <div><dt class="font-semibold">Días equivalentes:</dt><dd>{{ $ss->dias_equivalentes ?? '—' }}</dd></div>
      </dl>
    </div>

    <div class="md:col-span-2 rounded-3xl border bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-xl font-semibold mb-4 text-yellow-700 dark:text-yellow-300 flex items-center gap-2">
        <i class="fas fa-note-sticky"></i> Observaciones
      </h2>
      <div class="prose max-w-none text-sm dark:prose-invert whitespace-pre-line leading-relaxed text-gray-700 dark:text-slate-300">
        {!! nl2br(e($ss->observaciones ?? '—')) !!}
      </div>
    </div>
  </div>
@endsection
