{{-- resources/views/contracts/view.blade.php --}}
@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Contrato #{{ $contract->id }}</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Detalle del contrato.</p>
    </div>
    <a href="{{ route('contracts.index') }}"
       class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">
      Volver
    </a>
  </div>
@endsection

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow space-y-3">
      <div><span class="text-xs text-gray-500">Participante</span><div class="font-medium">{{ $contract->participant?->nombre ?? '—' }}</div></div>
      <div><span class="text-xs text-gray-500">Empresa</span><div class="font-medium">{{ $contract->company?->nombre ?? '—' }}</div></div>
      <div><span class="text-xs text-gray-500">Oferta</span><div class="font-medium">{{ $contract->offer?->puesto ? ('#'.$contract->offer->id.' '.$contract->offer->puesto) : '—' }}</div></div>
      <div><span class="text-xs text-gray-500">Tipo</span><div class="font-medium">{{ $contract->tipo_contrato ?? '—' }}</div></div>
      <div class="grid grid-cols-2 gap-4">
        <div>
          <div class="text-xs text-gray-500">Fecha inicio</div>
          <div class="font-medium">{{ $contract->fecha_inicio?->format('d/m/Y') ?? '—' }}</div>
        </div>
        <div>
          <div class="text-xs text-gray-500">Fecha fin prevista</div>
          <div class="font-medium">{{ $contract->fecha_fin_prevista?->format('d/m/Y') ?? '—' }}</div>
        </div>
      </div>
      <div><span class="text-xs text-gray-500">Jornada</span><div class="font-medium">{{ $contract->jornada_pct ? $contract->jornada_pct.'%' : '—' }}</div></div>
      <div><span class="text-xs text-gray-500">Registrado en Contrata</span><div class="font-medium">{{ $contract->registrado_contrata ? 'Sí' : 'No' }}</div></div>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow space-y-4">
      <div>
        <div class="text-xs text-gray-500">Documento del contrato</div>
        @if($contract->pdf)
          <div class="flex items-center gap-3">
            <span class="font-medium">#{{ $contract->pdf->id }} — {{ $contract->pdf->nombre_archivo }}</span>
            <a href="{{ route('documents.download', $contract->pdf) }}"
               class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 text-sm">Descargar</a>
          </div>
        @else
          <div class="text-gray-500">—</div>
        @endif
      </div>

      <div>
        <div class="text-xs text-gray-500">Alta SS</div>
        @if($contract->altaSS)
          <div class="flex items-center gap-3">
            <span class="font-medium">#{{ $contract->altaSS->id }} — {{ $contract->altaSS->nombre_archivo }}</span>
            <a href="{{ route('documents.download', $contract->altaSS) }}"
               class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 text-sm">Descargar</a>
          </div>
        @else
          <div class="text-gray-500">—</div>
        @endif
      </div>
    </div>
  </div>
@endsection
