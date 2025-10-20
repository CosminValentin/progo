@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-2xl font-bold">{{ $participant->nombre }}</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">DNI/NIE: {{ $participant->dni_nie }}</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('editparticipant', $participant) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow">Editar</a>
      <a href="{{ route('participants') }}" class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">Volver</a>
    </div>
  </div>
@endsection

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="rounded-xl border bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-lg font-semibold mb-4 text-indigo-700 dark:text-indigo-300">Datos de contacto</h2>
      <dl class="space-y-3 text-sm">
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Email</dt><dd class="font-medium">{{ $participant->email ?: '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Teléfono</dt><dd class="font-medium">{{ $participant->telefono ?: '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Provincia</dt><dd class="font-medium">{{ $participant->provincia ?: '—' }}</dd></div>
      </dl>
    </div>

    <div class="rounded-xl border bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-lg font-semibold mb-4 text-indigo-700 dark:text-indigo-300">Situación</h2>
      <dl class="space-y-3 text-sm">
        <div class="flex justify-between">
          <dt class="text-gray-500 dark:text-slate-400">Fecha alta programa</dt>
          <dd class="font-medium">{{ optional($participant->fecha_alta_prog)->format('d/m/Y') ?: '—' }}</dd>
        </div>
        <div class="flex justify-between">
          <dt class="text-gray-500 dark:text-slate-400">Estado</dt>
          <dd>
            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs
                         {{ $participant->estado === 'activo'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200'
                            : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-200' }}">
              {{ ucfirst($participant->estado ?? '—') }}
            </span>
          </dd>
        </div>
      </dl>
    </div>

    <div class="md:col-span-2 rounded-xl border bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-lg font-semibold mb-4 text-indigo-700 dark:text-indigo-300">Notas</h2>
      <div class="prose max-w-none text-sm dark:prose-invert">
        {!! nl2br(e($participant->notas ?? '—')) !!}
      </div>
    </div>
  </div>
@endsection
