@extends('layouts.auth')
@section('title','Job')

@section('content')
<div class="hero mb-4">
  <div class="flex items-end justify-between gap-4">
    <div>
      <h1 class="text-3xl font-extrabold">{{ $job->title }}</h1>
      <p class="text-white/90">{{ $job->company->nombre ?? '—' }}</p>
    </div>
    <div>
      <span class="px-3 py-1 rounded-lg text-sm bg-white/90 text-slate-800">
        {{ ucfirst($job->status) }}
      </span>
    </div>
  </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
  <div class="card lg:col-span-2">
    <h2 class="font-semibold mb-2">Descripción</h2>
    <div class="prose prose-sm max-w-none dark:prose-invert">
      {!! nl2br(e($job->description ?? '—')) !!}
    </div>
  </div>

  <div class="card space-y-2">
    <div><span class="text-sm text-slate-500">Empresa</span><div class="font-semibold">{{ $job->company->nombre ?? '—' }}</div></div>
    <div><span class="text-sm text-slate-500">Ubicación</span><div class="font-semibold">{{ $job->location ?? '—' }}</div></div>
    <div><span class="text-sm text-slate-500">Tipo</span><div class="font-semibold">{{ $job->type ?? '—' }}</div></div>
    <div><span class="text-sm text-slate-500">Salario</span><div class="font-semibold">
      @if($job->salary_min || $job->salary_max)
        {{ $job->salary_min ? number_format($job->salary_min,0,',','.') : '—' }}
        –
        {{ $job->salary_max ? number_format($job->salary_max,0,',','.') : '—' }} €
      @else — @endif
    </div></div>
    <div><span class="text-sm text-slate-500">Publicación</span><div class="font-semibold">{{ optional($job->posted_at)->format('d/m/Y') ?: '—' }}</div></div>

    <div class="pt-2 flex flex-wrap gap-2">
      <a href="{{ route('jobs.edit',$job) }}" class="btn-soft">Editar</a>
      <a href="{{ route('jobs.index') }}" class="btn-soft">Volver</a>
      <form method="POST" action="{{ route('jobs.destroy',$job) }}" onsubmit="return confirm('¿Eliminar este job?')">
        @csrf @method('DELETE')
        <button class="btn-danger">Borrar</button>
      </form>
    </div>
  </div>
</div>
@endsection
