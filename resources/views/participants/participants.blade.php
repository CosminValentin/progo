@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-800 dark:text-slate-100">Participantes</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">Gestiona altas, ediciones y bajas.</p>
    </div>
    <a href="{{ route('addparticipant') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow">
      <i class="fa-solid fa-plus"></i> Nuevo
    </a>
  </div>
@endsection

@section('content')
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow">
      <div class="text-sm text-gray-500 dark:text-slate-400">Total</div>
      <div class="mt-1 flex items-baseline gap-2">
        <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100">{{ number_format($participants->total()) }}</div>
        <span class="text-xs px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200">live</span>
      </div>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow">
      <div class="text-sm text-gray-500 dark:text-slate-400">Página</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800 dark:text-slate-100">{{ $participants->currentPage() }}/{{ $participants->lastPage() }}</div>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow">
      <div class="text-sm text-gray-500 dark:text-slate-400">Mostrando</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800 dark:text-slate-100">{{ $participants->count() }}</div>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow">
      <div class="text-sm text-gray-500 dark:text-slate-400">Estado</div>
      <div class="mt-1 flex items-center gap-2">
        <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-300 text-sm">
          <i class="fa-solid fa-circle text-[8px]"></i> Activo
        </span>
      </div>
    </div>
  </div>

  <form method="GET" action="{{ route('participants') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
    <div class="col-span-2">
      <div class="relative">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Buscar por DNI/NIE, nombre o email…"
               class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-9 py-2
                      placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
      </div>
    </div>
    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm hover:bg-gray-50 dark:hover:bg-slate-700">Buscar</button>
      <a href="{{ route('participants') }}" class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm hover:bg-gray-50 dark:hover:bg-slate-700">Limpiar</a>
    </div>
  </form>

  <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow">
    <table class="min-w-full">
      <thead class="bg-gray-50 dark:bg-slate-700/50 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-slate-300">
        <tr>
          <th class="px-4 py-3">DNI/NIE</th>
          <th class="px-4 py-3">Nombre</th>
          <th class="px-4 py-3 hidden md:table-cell">Email</th>
          <th class="px-4 py-3 hidden lg:table-cell">Provincia</th>
          <th class="px-4 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($participants as $p)
          <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
            <td class="px-4 py-3 text-sm font-semibold text-gray-800 dark:text-slate-100">{{ $p->dni_nie }}</td>
            <td class="px-4 py-3 text-sm text-gray-700 dark:text-slate-200">{{ $p->nombre }}</td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-300 hidden md:table-cell">{{ $p->email }}</td>
            <td class="px-4 py-3 text-sm text-gray-600 dark:text-slate-300 hidden lg:table-cell">
              <span class="inline-flex items-center gap-2">
                {{ $p->provincia }}
                @if($p->estado)
                  <span class="ml-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold tracking-wide
                               border border-emerald-200 bg-emerald-50 text-emerald-700
                               dark:border-emerald-700/40 dark:bg-emerald-900/30 dark:text-emerald-200">
                    {{ ucfirst($p->estado) }}
                  </span>
                @endif
              </span>
            </td>
            <td class="px-4 py-3 text-sm">
              <div class="flex justify-end gap-2" x-data="{ open:false }">
                <a href="{{ route('viewparticipant', $p) }}" class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-100 hover:bg-gray-50 dark:hover:bg-slate-700/60">Ver</a>
                <a href="{{ route('editparticipant', $p) }}" class="px-3 py-1.5 rounded-lg border border-indigo-200 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 dark:border-indigo-800 dark:text-indigo-200 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/40">Editar</a>
                <button @click="open=true" class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 dark:border-rose-800 dark:text-rose-200 dark:bg-rose-900/30 dark:hover:bg-rose-900/40">Borrar</button>

                <!-- Modal -->
                <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
                  <div class="absolute inset-0 bg-black/40" @click="open=false"></div>
                  <div x-transition class="relative w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/5 dark:ring-white/10">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
                      <h3 class="text-lg font-semibold text-center">Confirmar eliminación</h3>
                      <p class="mt-1 text-sm text-center text-gray-500 dark:text-slate-300">¿Eliminar <strong>{{ $p->nombre }}</strong> ({{ $p->dni_nie }})?</p>
                    </div>
                    <div class="px-6 py-5 flex flex-col sm:flex-row sm:justify-between gap-3">
                      <button @click="open=false" class="px-4 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700/60">Cancelar</button>
                      <form method="POST" action="{{ route('deleteparticipant', $p) }}">
                        @csrf
                        <button class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white hover:shadow dark:from-rose-500 dark:to-red-500">Sí, eliminar</button>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- /Modal -->
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-10 text-center text-gray-500 dark:text-slate-400">No hay participantes todavía.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    <div class="inline-block rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 shadow">
      {{ $participants->onEachSide(1)->links('pagination::tailwind') }}
    </div>
  </div>
@endsection
