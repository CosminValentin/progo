@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400">
      Detalle de candidatura #{{ $application->id }}
    </h1>
    <div class="flex gap-2">
      <a href="{{ route('applications.edit', $application) }}"
         class="px-4 py-2 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50">
        Editar
      </a>
      <a href="{{ route('applications.index') }}"
         class="px-4 py-2 rounded-lg border dark:border-slate-700">
        Volver
      </a>
    </div>
  </div>
@endsection

@section('content')
  <div class="rounded-2xl bg-white dark:bg-slate-800 shadow p-6 space-y-3">
    <div><span class="font-semibold">Fecha:</span> {{ optional($application->fecha)->format('d/m/Y') ?: '—' }}</div>
    <div><span class="font-semibold">Participante:</span> {{ $application->participant->nombre ?? '—' }}</div>
    <div><span class="font-semibold">Oferta:</span> {{ $application->offer->puesto ?? '—' }}</div>
    <div><span class="font-semibold">Empresa:</span> {{ $application->offer->company->nombre ?? '—' }}</div>
    <div>
      <span class="font-semibold">Estado:</span>
      {{ ucfirst(str_replace('_',' ',$application->estado ?? '—')) }}
    </div>
  </div>
@endsection
