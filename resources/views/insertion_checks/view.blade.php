@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Detalle del registro</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Información completa.</p>
    </div>
    <a href="{{ route('insertion_checks.index') }}"
       class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">
      Volver
    </a>
  </div>
@endsection

@section('content')
  <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <div class="text-xs text-gray-500">Fecha</div>
        <div class="font-medium">{{ optional($check->fecha)->format('d/m/Y H:i') }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Participante</div>
        <div class="font-medium">{{ $check->participant?->nombre ?? '—' }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Fuente</div>
        <div class="font-medium">{{ $check->fuente ?? '—' }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Periodo válido</div>
        <div class="font-medium">{{ $check->periodo_valido ? 'Sí' : 'No' }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Días válidos</div>
        <div class="font-medium">{{ $check->dias_validos ?? '—' }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Parcialidad</div>
        <div class="font-medium">{{ is_null($check->parcialidad) ? '—' : $check->parcialidad.'%' }}</div>
      </div>
      <div>
        <div class="text-xs text-gray-500">Válido 90 días</div>
        <div class="font-medium">{{ $check->valido_90_dias ? 'Sí' : 'No' }}</div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
      <div>
        <div class="text-xs text-gray-500 mb-1">Observaciones</div>
        <div class="rounded-lg border border-gray-200 dark:border-slate-700 p-3 bg-white/50 dark:bg-slate-900/50 min-h-[80px]">
          {{ $check->observaciones ?: '—' }}
        </div>
      </div>
      <div>
        <div class="text-xs text-gray-500 mb-1">Observaciones (internas)</div>
        <div class="rounded-lg border border-gray-200 dark:border-slate-700 p-3 bg-white/50 dark:bg-slate-900/50 min-h-[80px]">
          {{ $check->observaciones2 ?: '—' }}
        </div>
      </div>
    </div>

<div class="flex justify-end gap-2 mt-6" x-data="{open:false}">
  <a href="{{ route('insertion_checks.edit', $check) }}"
     class="px-4 py-2 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 transition">
    Editar
  </a>

  <button type="button"
          @click="open=true"
          class="px-4 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition">
    Borrar
  </button>

  <!-- Modal -->
  <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>

    <div x-transition
         class="relative w-full max-w-md rounded-xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
      <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
        <h3 class="text-lg font-semibold text-center text-red-600 dark:text-red-400">Confirmar eliminación</h3>
        <p class="mt-2 text-sm text-center text-gray-600 dark:text-slate-300">
          ¿Eliminar este registro?
          <br>
          <span class="text-xs opacity-80">
            {{ optional($check->fecha)->format('d/m/Y') }} · {{ $check->participant?->nombre ?? '—' }}
          </span>
        </p>
      </div>
      <div class="flex items-center justify-center gap-4 p-6">
        <form method="POST" action="{{ route('insertion_checks.destroy', $check) }}">
          @csrf
          @method('DELETE')
          <button
            class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white hover:shadow dark:from-rose-500 dark:to-red-500">
            Sí, eliminar
          </button>
        </form>
        <button type="button"
                @click="open=false"
                class="px-6 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-600 dark:hover:bg-slate-700 rounded-lg">
          Cancelar
        </button>
      </div>
    </div>
  </div>
  <!-- /Modal -->
</div>

  </div>
@endsection
