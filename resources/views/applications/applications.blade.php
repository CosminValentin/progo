@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Candidaturas</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Listado global de candidaturas.</p>
    </div>
    <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800">
      Volver
    </a>
  </div>
@endsection

@section('content')
  @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 p-3 text-emerald-800 dark:border-emerald-700/40 dark:bg-emerald-900/20 dark:text-emerald-200">
      {{ session('success') }}
    </div>
  @endif

  <form method="GET" action="{{ route('applications.index') }}" class="mb-4">
    <div class="flex gap-2">
      <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Buscar por participante, oferta, empresa o estado"
             class="w-full rounded-lg border px-3 py-2 dark:bg-slate-900 dark:border-slate-700">
      <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white">Buscar</button>
    </div>
  </form>

  <div class="overflow-x-auto rounded-2xl border border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 shadow">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">Fecha</th>
          <th class="px-5 py-3 text-left">Participante</th>
          <th class="px-5 py-3 text-left">Oferta</th>
          <th class="px-5 py-3 text-left">Empresa</th>
          <th class="px-5 py-3 text-left">Estado</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($applications as $a)
          <tr>
            <td class="px-5 py-4 whitespace-nowrap">{{ optional($a->fecha)->format('d/m/Y') ?: '—' }}</td>
            <td class="px-5 py-4">{{ $a->participant->nombre ?? '—' }}</td>
            <td class="px-5 py-4">{{ $a->offer->puesto ?? '—' }}</td>
            <td class="px-5 py-4">{{ $a->offer->company->nombre ?? '—' }}</td>
            <td class="px-5 py-4 text-gray-600 dark:text-slate-400">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                @switch($a->estado)
                  @case('pendiente') bg-amber-100 text-amber-700 @break
                  @case('en_proceso') bg-blue-100 text-blue-700 @break
                  @case('aceptada') bg-emerald-100 text-emerald-700 @break
                  @case('rechazada') bg-rose-100 text-rose-700 @break
                  @default bg-gray-100 text-gray-700
                @endswitch">
                {{ ucfirst(str_replace('_',' ',$a->estado ?? '—')) }}
              </span>
            </td>
            <td class="px-5 py-4">
              <div class="flex justify-end gap-2" x-data="{open:false}">
                <a href="{{ route('applications.show', $a) }}"
                   class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition">Ver</a>
                <a href="{{ route('applications.edit', $a) }}"
                   class="px-3 py-2 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 transition">Editar</a>
                <form method="POST" action="{{ route('applications.destroy', $a) }}"
                      onsubmit="return confirm('¿Eliminar esta candidatura?');">
                  @csrf
                  @method('DELETE')
                  <button class="px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition">
                    Borrar
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-5 py-6 text-center text-gray-500">No hay candidaturas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $applications->links() }}
  </div>
@endsection
