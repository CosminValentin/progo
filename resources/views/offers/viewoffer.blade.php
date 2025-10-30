@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-2xl font-bold">{{ $offer->display_title }}</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">Fecha: {{ optional($offer->fecha)->format('d/m/Y') }}</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('offers.edit', $offer) }}"
         class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow">Editar</a>
      <a href="{{ route('offers.index') }}"
         class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">Volver</a>
    </div>
  </div>
@endsection

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="rounded-xl border bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-lg font-semibold mb-4 text-indigo-700 dark:text-indigo-300">Empresa</h2>
      <dl class="space-y-3 text-sm">
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Nombre</dt><dd class="font-medium">{{ $offer->company->nombre ?? '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">CIF/NIF</dt><dd class="font-medium">{{ $offer->company->cif_nif ?? '—' }}</dd></div>
      </dl>
    </div>

    <div class="rounded-xl border bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-lg font-semibold mb-4 text-indigo-700 dark:text-indigo-300">Oferta</h2>
      <dl class="space-y-3 text-sm">
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Puesto</dt><dd class="font-medium">{{ $offer->puesto }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Tipo contrato</dt><dd class="font-medium">{{ $offer->tipo_contrato ?: '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">% Jornada</dt><dd class="font-medium">{{ $offer->jornada_pct ? $offer->jornada_pct.'%' : '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Ubicación</dt><dd class="font-medium">{{ $offer->ubicacion ?: '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Estado</dt>
          <dd><span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs {{ $offer->estado_badge_classes }}">{{ ucfirst(str_replace('_',' ',$offer->estado ?? '')) }}</span></dd>
        </div>
      </dl>
    </div>

    <div class="md:col-span-2 rounded-xl border bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
      <h2 class="text-lg font-semibold mb-4 text-indigo-700 dark:text-indigo-300">Requisitos</h2>
      <div class="prose max-w-none text-sm dark:prose-invert">
        {!! nl2br(e($offer->requisitos ?? '—')) !!}
      </div>
    </div>
  </div>
@endsection
