@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400 flex items-center gap-2">
        <i class="fa-solid fa-handshake text-indigo-500"></i>
        Convenio
      </h1>
      <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
        Detalle del convenio seleccionado.
      </p>
    </div>

    <div class="flex flex-wrap items-center gap-2">
      <a href="{{ route('agreements.index') }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 
                bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-sm font-medium shadow-sm transition">
        <i class="fa-solid fa-arrow-left text-gray-500 dark:text-slate-400"></i> Volver
      </a>

      <a href="{{ route('agreements.edit', $agreement) }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 
                dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 text-sm font-medium transition">
        <i class="fa-solid fa-pen"></i> Editar
      </a>
    </div>
  </div>
@endsection

@section('content')
  @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-100 shadow flex items-center gap-2">
      <i class="fa-solid fa-circle-check text-emerald-500"></i>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 p-4 text-rose-800 dark:border-rose-600 dark:bg-rose-900/40 dark:text-rose-100 shadow flex items-center gap-2">
      <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 p-6 md:p-8 shadow-lg space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <p class="text-xs uppercase text-gray-500 dark:text-slate-400 flex items-center gap-1">
          <i class="fa-solid fa-building"></i> Empresa
        </p>
        <p class="text-base font-medium text-gray-800 dark:text-slate-200">{{ $agreement->company?->nombre ?? '—' }}</p>
      </div>

      <div>
        <p class="text-xs uppercase text-gray-500 dark:text-slate-400 flex items-center gap-1">
          <i class="fa-solid fa-calendar-check"></i> Fecha firma
        </p>
        <p class="text-base">{{ $agreement->fecha_firma?->format('d/m/Y') ?? '—' }}</p>
      </div>

      <div>
        <p class="text-xs uppercase text-gray-500 dark:text-slate-400 flex items-center gap-1">
          <i class="fa-solid fa-user-tie"></i> Firmado (Agencia)
        </p>
        <p class="text-base">@if($agreement->firmado_agencia) ✅ Sí @else ❌ No @endif</p>
      </div>

      <div>
        <p class="text-xs uppercase text-gray-500 dark:text-slate-400 flex items-center gap-1">
          <i class="fa-solid fa-building-user"></i> Firmado (Empresa)
        </p>
        <p class="text-base">@if($agreement->firmado_empresa) ✅ Sí @else ❌ No @endif</p>
      </div>

      <div>
        <p class="text-xs uppercase text-gray-500 dark:text-slate-400 flex items-center gap-1">
          <i class="fa-solid fa-hourglass-half"></i> Validez
        </p>
        <p class="text-base">
          {{ $agreement->validez_desde?->format('d/m/Y') ?? '—' }} — {{ $agreement->validez_hasta?->format('d/m/Y') ?? '—' }}
          @if($agreement->vigente)
            <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200">Vigente</span>
          @endif
        </p>
      </div>

      <div>
        <p class="text-xs uppercase text-gray-500 dark:text-slate-400 flex items-center gap-1">
          <i class="fa-solid fa-file-pdf"></i> Documento
        </p>
        <p class="text-base">
          @if($agreement->pdf)
            {{ $agreement->pdf->nombre_archivo }}
            @if(Route::has('documents.download'))
              <a href="{{ route('documents.download', $agreement->pdf) }}"
                 class="ml-2 text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1 text-sm">
                <i class="fa-solid fa-download"></i> Descargar
              </a>
            @endif
          @else
            —
          @endif
        </p>
      </div>
    </div>

    {{-- Borrar --}}
    <div x-data="{open:false}" class="pt-4">
      <button type="button" @click="open=true"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200 
                     dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50 text-sm transition">
        <i class="fa-solid fa-trash-can"></i> Borrar convenio
      </button>

      <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>
        <div x-transition class="relative w-full max-w-md rounded-xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
          <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700 text-center">
            <i class="fa-solid fa-triangle-exclamation text-4xl text-rose-500 mb-2"></i>
            <h3 class="text-lg font-semibold text-rose-700 dark:text-rose-400">Confirmar eliminación</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-slate-300">
              ¿Seguro que deseas eliminar este convenio? Esta acción no se puede deshacer.
            </p>
          </div>
          <div class="flex items-center justify-center gap-4 p-6">
            <form method="POST" action="{{ route('agreements.destroy', $agreement) }}">
              @csrf @method('DELETE')
              <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 
                             text-white hover:shadow dark:from-rose-500 dark:to-red-500 transition">
                <i class="fa-solid fa-trash"></i> Sí, eliminar
              </button>
            </form>
            <button type="button" @click="open=false"
                    class="inline-flex items-center gap-2 px-6 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 
                           dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-200 transition">
              <i class="fa-solid fa-xmark"></i> Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
