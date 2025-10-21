@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-2xl font-bold">Candidatura</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">
        Fecha: {{ optional($application->fecha)->format('d/m/Y') }}
      </p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('editapplication', $application) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow">Editar</a>
      <a href="{{ route('applications') }}" class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">Volver</a>
    </div>
  </div>
@endsection

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="rounded-xl border bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-lg font-semibold mb-4 text-indigo-700 dark:text-indigo-300">Participante</h2>
      <dl class="space-y-3 text-sm">
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Nombre</dt><dd class="font-medium">{{ $application->participant->nombre ?? '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">DNI/NIE</dt><dd class="font-medium">{{ $application->participant->dni_nie ?? '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Email</dt><dd class="font-medium">{{ $application->participant->email ?? '—' }}</dd></div>
      </dl>
    </div>

    <div class="rounded-xl border bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-lg font-semibold mb-4 text-indigo-700 dark:text-indigo-300">Oferta</h2>
      <dl class="space-y-3 text-sm">
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Título</dt><dd class="font-medium">{{ $application->offer->titulo ?? $application->offer->nombre ?? ('Oferta #'.$application->offer_id) }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">ID</dt><dd class="font-medium">{{ $application->offer->id ?? $application->offer_id }}</dd></div>
      </dl>
    </div>

    <div class="md:col-span-2 rounded-xl border bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-lg font-semibold mb-4 text-indigo-700 dark:text-indigo-300">Estado</h2>
      <div class="text-sm">
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs {{ $application->estado_badge_classes }}">
          {{ ucfirst(str_replace('_',' ',$application->estado ?? '')) }}
        </span>
      </div>
    </div>
  </div>
@endsection
