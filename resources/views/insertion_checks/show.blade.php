@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
  <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
    <div class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 p-8 text-white">
      <h1 class="text-3xl font-bold tracking-tight">Detalle de comprobación</h1>
      <p class="text-white/90 mt-1">Resumen del estado y evidencias</p>
    </div>

    <div class="p-6 md:p-8 space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <div class="text-sm text-gray-500 mb-1">Participante</div>
          <div class="font-semibold text-gray-900">{{ $insertion_check->participant->nombre ?? '—' }}</div>
        </div>

        <div>
          <div class="text-sm text-gray-500 mb-1">Fecha</div>
          <div class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($insertion_check->fecha)->format('d/m/Y') }}</div>
        </div>

        <div>
          <div class="text-sm text-gray-500 mb-1">Días válidos</div>
          <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
            {{ $insertion_check->dias_validos ?? '—' }} días
          </div>
        </div>

        <div>
          <div class="text-sm text-gray-500 mb-1">Parcialidad</div>
          <div class="font-semibold text-gray-900">{{ $insertion_check->parcialidad ?? '—' }}%</div>
        </div>

        <div>
          <div class="text-sm text-gray-500 mb-1">Fuente</div>
          <div class="font-semibold text-gray-900">{{ $insertion_check->fuente ?? '—' }}</div>
        </div>

        <div class="space-y-2">
          <div>
            <span class="text-sm text-gray-500">Periodo válido</span>
            @if($insertion_check->periodo_valido)
              <span class="ml-2 px-2.5 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">Sí</span>
            @else
              <span class="ml-2 px-2.5 py-1 rounded-full text-xs bg-gray-100 text-gray-600">No</span>
            @endif
          </div>
          <div>
            <span class="text-sm text-gray-500">Válido 90 días</span>
            @if($insertion_check->valido_90_dias)
              <span class="ml-2 px-2.5 py-1 rounded-full text-xs bg-green-100 text-green-700">Válido</span>
            @else
              <span class="ml-2 px-2.5 py-1 rounded-full text-xs bg-rose-100 text-rose-700">No válido</span>
            @endif
          </div>
        </div>
      </div>

      <div>
        <div class="text-sm text-gray-500 mb-1">Observaciones</div>
        <div class="p-4 bg-gray-50 rounded-xl text-sm text-gray-700">
          {{ $insertion_check->observaciones ?? 'Sin observaciones' }}
        </div>
      </div>

      <div class="flex flex-wrap gap-3 pt-2">
        <a href="{{ route('insertion_checks.edit', $insertion_check) }}"
           class="inline-flex items-center px-5 py-2.5 rounded-2xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition shadow">
          Editar
        </a>
        <a href="{{ route('insertion_checks.index') }}"
           class="inline-flex items-center px-5 py-2.5 rounded-2xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
          Volver
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
