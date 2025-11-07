@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Participantes</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Gestiona altas, ediciones y bajas.</p>
    </div>
    <a href="{{ route('addparticipant') }}"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow transition">
      <i class="fa-solid fa-plus"></i> Nuevo
    </a>
  </div>
@endsection

@section('content')
<!-- 🔹 Cards resumen -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 via-purple-600 to-fuchsia-600 p-6 text-white shadow transition">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,white,transparent_70%)] opacity-20"></div>
    <div class="flex items-center gap-4 relative z-10">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm transition">
        <i class="fa-solid fa-users-line text-2xl"></i>
      </div>
      <div>
        <p class="text-sm opacity-80">Total Participantes</p>
        <p class="text-3xl font-bold">{{ $totalParticipants ?? 0 }}</p>
      </div>
    </div>
  </div>

  {{-- Activos --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-700 p-6 text-white shadow transition">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,white,transparent_70%)] opacity-15"></div>
    <div class="flex items-center gap-4 relative z-10">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm transition">
        <i class="fa-solid fa-user-check text-2xl"></i>
      </div>
      <div>
        <p class="text-sm opacity-80">Activos</p>
        <p class="text-3xl font-bold">{{ $activeParticipants ?? 0 }}</p>
      </div>
    </div>
  </div>

  {{-- Pendientes --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-400 via-orange-500 to-red-500 p-6 text-white shadow transition">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,white,transparent_70%)] opacity-20"></div>
    <div class="flex items-center gap-4 relative z-10">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm transition">
        <i class="fa-solid fa-hourglass-half text-2xl"></i>
      </div>
      <div>
        <p class="text-sm opacity-80">Pendientes</p>
        <p class="text-3xl font-bold">{{ $pendingParticipants ?? 0 }}</p>
      </div>
    </div>
  </div>

  {{-- Inactivos --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 via-pink-600 to-violet-600 p-6 text-white shadow transition">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,white,transparent_70%)] opacity-20"></div>
    <div class="flex items-center gap-4 relative z-10">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm transition">
        <i class="fa-solid fa-user-xmark text-2xl"></i>
      </div>
      <div>
        <p class="text-sm opacity-80">Inactivos</p>
        <p class="text-3xl font-bold">{{ $inactiveParticipants ?? 0 }}</p>
      </div>
    </div>
  </div>
</div>

<!-- 🔍 Buscador -->
<form id="searchForm" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4" onsubmit="return false;">
  <div class="col-span-2">
    <div class="relative">
      <input type="text" id="searchInput" name="q" value="{{ $q ?? '' }}"
             placeholder="Buscar por DNI/NIE, nombre o email…"
             class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white/80 dark:bg-slate-800/80 text-sm px-10 py-3 backdrop-blur-sm placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow transition">
      <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 dark:text-indigo-400"></i>
    </div>
  </div>
  <div class="flex gap-3">
    <button type="button" id="searchButton"
            class="flex-1 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow transition focus:ring-2 focus:ring-indigo-500">
      <i class="fa-solid fa-search mr-2"></i> Buscar
    </button>
    <button type="button" id="clearButton"
            class="flex-1 px-5 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white/70 dark:bg-slate-800/70 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 shadow transition focus:ring-2 focus:ring-indigo-500">
      <i class="fa-solid fa-eraser mr-2"></i> Limpiar
    </button>
  </div>
</form>

{{-- Tabla compacta --}}
<div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow overflow-hidden">
  <table id="participantsTable" class="min-w-full table-fixed border-collapse">
    <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-slate-700/50 dark:to-slate-800 text-xs font-semibold uppercase text-indigo-700 dark:text-slate-200">
      <tr>
        <th class="px-3 py-3 w-10">#</th>
        <th class="px-3 py-3 w-24">DNI/NIE</th>
        <th class="px-3 py-3 w-40">Nombre</th>
        <th class="px-3 py-3 hidden md:table-cell w-28">Teléfono</th>
        <th class="px-3 py-3 hidden md:table-cell w-40">Email</th>
        <th class="px-3 py-3 hidden lg:table-cell w-24">Alta</th>
        <th class="px-3 py-3 hidden lg:table-cell w-24">Provincia</th>
        <th class="px-3 py-3 hidden lg:table-cell w-24">Estado</th>
        <th class="px-3 py-3 hidden xl:table-cell w-32">Tutor</th>
        <th class="px-3 py-3 w-40 text-right">Acciones</th>
      </tr>
    </thead>

    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
      @forelse($participants as $p)
        <tr class="hover:bg-indigo-50 dark:hover:bg-slate-700/30 transition">
          <td class="px-3 py-3 text-gray-700 dark:text-slate-300 text-center">{{ $p->id }}</td>
          <td class="px-3 py-3 font-semibold text-indigo-700 dark:text-indigo-300">{{ $p->dni_nie }}</td>
          <td class="px-3 py-3 text-gray-700 dark:text-slate-300 truncate">{{ $p->nombre }}</td>
          <td class="px-3 py-3 hidden md:table-cell text-gray-600 dark:text-slate-400">{{ $p->telefono }}</td>
          <td class="px-3 py-3 hidden md:table-cell text-gray-600 dark:text-slate-400 truncate">{{ $p->email }}</td>
          <td class="px-3 py-3 hidden lg:table-cell text-gray-600 dark:text-slate-400">{{ $p->fecha_alta_prog }}</td>
          <td class="px-3 py-3 hidden lg:table-cell text-gray-600 dark:text-slate-400">{{ $p->provincia }}</td>
          <td class="px-3 py-3 hidden lg:table-cell text-gray-600 dark:text-slate-400">{{ ucfirst($p->estado) }}</td>
          <td class="px-3 py-3 hidden xl:table-cell text-gray-600 dark:text-slate-400 truncate">{{ $p->tutor?->name ?? '—' }}</td>
          <td class="px-3 py-3 text-right whitespace-nowrap">
            <div class="flex justify-end gap-1 sm:gap-2" x-data="{ open:false }">
              <a href="{{ route('participants.timeline', $p) }}"
                class="px-3 py-1.5 rounded-lg bg-indigo-100 text-indigo-700 hover:bg-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50 transition text-sm"
                title="Ver timeline">
                <i class="fa-solid fa-timeline mr-1"></i> Timeline
              </a>

              <a href="{{ route('viewparticipant', $p) }}"
                class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition text-sm">
                Ver
              </a>

              <a href="{{ route('editparticipant', $p) }}"
                class="px-3 py-1.5 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 transition text-sm">
                Editar
              </a>

              <button @click="open = true"
                      class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition text-sm">
                Borrar
              </button>

              <!-- Modal borrar -->
              <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>

                <div x-transition
                    class="relative w-full max-w-md rounded-xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
                  <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-center text-red-600 dark:text-red-400">Confirmar eliminación</h3>
                    <p class="mt-2 text-sm text-center text-gray-600 dark:text-slate-300">
                      ¿Eliminar <strong>{{ $p->nombre }}</strong> ({{ $p->dni_nie }})? Esta acción no se puede deshacer.
                    </p>
                  </div>

                  <div class="flex items-center justify-center gap-4 p-6">
                    <form method="POST" action="{{ route('deleteparticipant', $p) }}">
                      @csrf
                      {{-- Si tu ruta espera DELETE, descomenta la siguiente línea --}}
                      {{-- @method('DELETE') --}}
                      <button class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white hover:shadow dark:from-rose-500 dark:to-red-500">
                        Sí, eliminar
                      </button>
                    </form>

                    <button @click="open=false"
                            class="px-6 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-600 dark:hover:bg-slate-700 rounded-lg">
                      Cancelar
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </td>

        </tr>
      @empty
        <tr>
          <td colspan="11" class="px-5 py-4 text-center text-gray-600 dark:text-slate-400">No se encontraron participantes.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
