@extends('layouts.auth')
@section('title','Jobs')

@section('content')
<div class="mb-4 flex items-center justify-between">
  <form method="GET" class="flex items-center gap-2">
    <input class="input w-64" name="q" value="{{ $q }}" placeholder="Buscar título o ubicación…">
    <button class="btn-soft">Buscar</button>
    @if($q !== '') <a href="{{ route('jobs.index') }}" class="btn-soft">Reset</a> @endif
  </form>
  <a href="{{ route('jobs.create') }}" class="btn-primary">＋ Nuevo Job</a>
</div>

@if(session('ok'))
  <div class="card mb-4 text-emerald-700 bg-emerald-50 border border-emerald-200"> {{ session('ok') }} </div>
@endif

<div class="card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="table">
      <thead>
        <tr>
          <th class="text-left">Título</th>
          <th class="text-left">Empresa</th>
          <th class="text-left">Ubicación</th>
          <th class="text-center">Tipo</th>
          <th class="text-center">Estado</th>
          <th class="text-center">Fecha</th>
          <th class="text-right">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($jobs as $j)
          <tr>
            <td class="font-semibold">{{ $j->title }}</td>
            <td>{{ $j->company->nombre ?? '—' }}</td>
            <td>{{ $j->location ?? '—' }}</td>
            <td class="text-center">{{ $j->type ?? '—' }}</td>
            <td class="text-center">
              <span class="px-2 py-1 rounded-lg text-xs {{ $j->status==='open' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                {{ ucfirst($j->status) }}
              </span>
            </td>
            <td class="text-center">{{ optional($j->posted_at)->format('d/m/Y') }}</td>
            <td class="text-right">
              <div class="inline-flex gap-2">
                <a href="{{ route('jobs.show',$j) }}" class="btn-soft">Ver</a>
                <a href="{{ route('jobs.edit',$j) }}" class="btn-soft">Editar</a>
                <form method="POST" action="{{ route('jobs.destroy',$j) }}" onsubmit="return confirm('¿Eliminar este job?')">
                  @csrf @method('DELETE')
                  <button class="btn-danger">Borrar</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center py-8 text-slate-500">Sin jobs.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-4 py-3 border-t border-white/30 dark:border-white/10">{{ $jobs->links() }}</div>
</div>
@endsection
