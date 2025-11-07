@extends('layouts.auth')
@section('title','Detalles del Contrato')

@section('content')
<div class="hero mb-6">
  <div class="flex flex-wrap justify-between items-end gap-4">
    <div>
      <h1 class="text-3xl font-extrabold">Contrato #{{ $contract->id }}</h1>
      <p class="text-white/80 text-sm">{{ $contract->participant->nombre ?? '' }} — {{ $contract->company->nombre ?? '' }}</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('contracts.edit',$contract) }}" class="btn-soft">✏️ Editar</a>
      <a href="{{ route('contracts.index') }}" class="btn-soft">← Volver</a>
      <form method="POST" action="{{ route('contracts.destroy',$contract) }}" onsubmit="return confirm('¿Eliminar este contrato?')">
        @csrf @method('DELETE')
        <button class="btn-danger">🗑️ Eliminar</button>
      </form>
    </div>
  </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
  <div class="card lg:col-span-2 space-y-4">
    <div class="grid sm:grid-cols-2 gap-4">
      <div>
        <h2 class="font-semibold text-slate-500 text-sm uppercase">Tipo de Contrato</h2>
        <p class="font-bold text-lg">{{ $contract->tipo_contrato ?? '—' }}</p>
      </div>
      <div>
        <h2 class="font-semibold text-slate-500 text-sm uppercase">Jornada</h2>
        <p class="font-bold text-lg">{{ $contract->jornada_pct ? $contract->jornada_pct.' %' : '—' }}</p>
      </div>
      <div>
        <h2 class="font-semibold text-slate-500 text-sm uppercase">Inicio</h2>
        <p>{{ optional($contract->fecha_inicio)->format('d/m/Y') }}</p>
      </div>
      <div>
        <h2 class="font-semibold text-slate-500 text-sm uppercase">Fin Prevista</h2>
        <p>{{ optional($contract->fecha_fin_prevista)->format('d/m/Y') ?: '—' }}</p>
      </div>
    </div>

    <div class="pt-4 border-t border-white/20">
      <h2 class="font-semibold text-slate-500 text-sm uppercase mb-2">PDF del Contrato</h2>
      @if($contract->pdf)
        <div class="flex flex-wrap items-center gap-3 mb-4">
          <a href="{{ route('contracts.pdf', $contract) }}" class="btn-primary">📄 Descargar PDF</a>
          <span class="text-slate-500 text-sm">{{ $contract->pdf->filename }}</span>
        </div>
        <iframe src="{{ route('contracts.pdf', $contract) }}" class="w-full h-[600px] rounded-xl border border-white/20 shadow-inner"></iframe>
      @else
        <p class="text-slate-400">No hay PDF adjunto.</p>
      @endif
    </div>
  </div>

  <div class="card space-y-4">
    <h2 class="font-semibold text-slate-600 dark:text-slate-300">Acciones rápidas</h2>
    <div class="flex flex-col gap-2">
      <a href="{{ route('contracts.edit',$contract) }}" class="btn-primary">✏️ Editar</a>
      <a href="{{ route('contracts.index') }}" class="btn-soft">← Volver</a>
      @if($contract->pdf)
        <a href="{{ route('contracts.pdf',$contract) }}" class="btn-soft">📄 Descargar PDF</a>
      @endif
    </div>
  </div>
</div>
@endsection
