@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Validación Inserciones</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Listado y búsqueda rápida.</p>
    </div>

    <a href="{{ route('insertion_checks.create') }}"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow">
      <i class="fa-solid fa-plus"></i> Nuevo registro
    </a>
  </div>
@endsection

@section('content')
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
    <div class="rounded-2xl p-6 text-white bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 shadow">
      <p class="text-sm opacity-80">Total registros</p>
      <p class="text-3xl font-bold">{{ $total }}</p>
    </div>
    <div class="rounded-2xl p-6 text-white bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 shadow">
      <p class="text-sm opacity-80">Válidos 90 días</p>
      <p class="text-3xl font-bold">{{ $validos90 }}</p>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-600 dark:bg-emerald-900 dark:text-emerald-100 shadow">
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 p-4 text-rose-800 dark:border-rose-600 dark:bg-rose-900 dark:text-rose-100 shadow">
      {{ session('error') }}
    </div>
  @endif

  {{-- Buscador instantáneo --}}
  <form id="icSearchForm" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4" onsubmit="return false;">
    <div class="col-span-2">
      <div class="relative">
        <input type="text" id="icSearchInput" placeholder="Buscar por participante, fuente, observaciones…"
               class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm px-10 py-3 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-md transition-all duration-300 hover:shadow-lg">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
      </div>
    </div>
    <div class="flex gap-3">
      <button type="button" id="icSearchButton"
              class="flex-1 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 text-white hover:from-indigo-700 hover:to-indigo-800 transition-all duration-300 shadow-md focus:ring-2 focus:ring-indigo-500">
        Buscar
      </button>
      <button type="button" id="icClearButton"
              class="flex-1 px-5 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all duration-300 shadow-md focus:ring-2 focus:ring-indigo-500">
        Limpiar
      </button>
    </div>
  </form>

  <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl overflow-auto">
    <table id="icTable" class="min-w-full table-auto border-collapse">
      <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-slate-700/50 dark:to-slate-800 text-xs font-semibold uppercase text-indigo-700 dark:text-slate-200">
        <tr>
          <th class="px-4 py-3 text-left w-28">Fecha</th>
          <th class="px-4 py-3 text-left w-48">Participante</th>
          <th class="px-4 py-3 text-left w-28">Fuente</th>
          <th class="px-4 py-3 text-left w-24">Días</th>
          <th class="px-4 py-3 text-left w-28">Parcialidad</th>
          <th class="px-4 py-3 text-left w-28">90 días</th>
          <th class="px-4 py-3 text-left">Observaciones</th>
          <th class="px-4 py-3 text-center w-44">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
        @forelse($checks as $c)
          <tr class="hover:bg-indigo-50 dark:hover:bg-slate-700/30 transition">
            <td class="px-4 py-3 whitespace-nowrap">{{ optional($c->fecha)->format('d/m/Y') }}</td>
            <td class="px-4 py-3 truncate max-w-[200px]">{{ $c->participant?->nombre ?? '—' }}</td>
            <td class="px-4 py-3">{{ $c->fuente ?? '—' }}</td>
            <td class="px-4 py-3">{{ $c->dias_validos ?? '—' }}</td>
            <td class="px-4 py-3">
              @if(!is_null($c->parcialidad)) {{ $c->parcialidad }}% @else — @endif
            </td>
            <td class="px-4 py-3">
              @if($c->valido_90_dias)
                <span class="text-emerald-600 font-bold">✔</span>
              @else
                <span class="text-rose-600 font-bold">✖</span>
              @endif
            </td>
            <td class="px-4 py-3 truncate max-w-[420px]" title="{{ $c->observaciones }}">{{ \Illuminate\Support\Str::limit($c->observaciones, 120) }}</td>
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2" x-data="{open:false}">
                <a href="{{ route('insertion_checks.show', $c) }}"
                  class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition text-sm">
                  Ver
                </a>
                <a href="{{ route('insertion_checks.edit', $c) }}"
                  class="px-3 py-1.5 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 transition text-sm">
                  Editar
                </a>

                <!-- Botón abre modal -->
                <button type="button"
                        @click="open=true"
                        class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition text-sm">
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
                        ¿Eliminar este registro de inserción?
                        <br>
                        <span class="text-xs opacity-80">
                          {{ optional($c->fecha)->format('d/m/Y') }} · {{ $c->participant?->nombre ?? '—' }}
                        </span>
                      </p>
                    </div>
                    <div class="flex items-center justify-center gap-4 p-6">
                      <form method="POST" action="{{ route('insertion_checks.destroy', $c) }}">
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
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="px-5 py-6 text-center text-gray-600 dark:text-slate-400">No hay registros.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $checks->links() }}
  </div>
@endsection

@section('scripts')
<script>
  // Buscador instantáneo (cliente)
  function initICSearchForm(){
    const form = document.querySelector('#icSearchForm');
    form?.addEventListener('submit', e => e.preventDefault());
  }
  function initICSearchInput(){
    const input  = document.querySelector('#icSearchInput');
    const btn    = document.querySelector('#icSearchButton');
    const clear  = document.querySelector('#icClearButton');
    const rows   = () => document.querySelectorAll('#icTable tbody tr');

    const filter = () => {
      const q = (input?.value || '').toLowerCase().trim();
      rows().forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = q === '' ? '' : (text.includes(q) ? '' : 'none');
      });
    };

    input?.addEventListener('input', filter);
    btn?.addEventListener('click', filter);
    clear?.addEventListener('click', () => { input.value = ''; filter(); });
  }
  document.addEventListener('DOMContentLoaded', () => {
    initICSearchForm();
    initICSearchInput();
  });
</script>
@endsection
