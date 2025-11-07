{{-- resources/views/contracts/index.blade.php --}}
@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Contratos</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Listado y búsqueda rápida.</p>
    </div>
    <a href="{{ route('contracts.create') }}"
       class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow">
      Nuevo contrato
    </a>
  </div>
@endsection

@section('content')
  {{-- Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
    <div class="rounded-2xl p-6 text-white bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 shadow">
      <p class="text-sm opacity-80">Total</p>
      <p class="text-3xl font-bold">{{ $total }}</p>
      <span class="text-xs px-3 py-1 rounded bg-white/20">Contracto registradas</span>
    </div>

    <!-- Empresas vigentes -->
    <div class="rounded-2xl p-6 text-white bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 shadow">
      <p class="text-sm opacity-80">Vigentes</p>
      <p class="text-3xl font-bold">{{ $vigentes }}</p>
      <span class="text-xs px-3 py-1 rounded bg-white/20">Activas actualmente</span>
    </div>



    
  </div>

  {{-- Flash --}}
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

  {{-- Buscador (cliente) --}}
  <form id="searchForm" class="mb-6 max-w-3xl" onsubmit="return false;">
    <div class="relative">
      <input type="text" id="searchInput" value="{{ $q ?? '' }}" placeholder="Buscar por participante, empresa, oferta, tipo…"
             class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm px-10 py-3 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-md transition-all duration-300 hover:shadow-lg">
      <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
      <button type="button" id="clearButton"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 hover:underline">Limpiar</button>
    </div>
  </form>

  {{-- Tabla --}}
  <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl overflow-hidden">
    <table id="contractsTable" class="min-w-full table-auto border-collapse">
      <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-slate-700/50 dark:to-slate-800 text-xs font-semibold uppercase text-indigo-700 dark:text-slate-200">
        <tr>
          <th class="px-3 py-3 w-16 text-left">#</th>
          <th class="px-3 py-3 text-left">Participante</th>
          <th class="px-3 py-3 text-left">Empresa</th>
          <th class="px-3 py-3 text-left">Oferta</th>
          <th class="px-3 py-3 w-28 text-left">Inicio</th>
          <th class="px-3 py-3 w-32 text-left">Fin prevista</th>
          <th class="px-3 py-3 w-28 text-left">Jornada %</th>
          <th class="px-3 py-3 w-36 text-left">Tipo</th>
          <th class="px-3 py-3 w-40 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
        @forelse($contracts as $c)
          <tr class="hover:bg-indigo-50 dark:hover:bg-slate-700/30 transition">
            <td class="px-3 py-2">#{{ $c->id }}</td>
            <td class="px-3 py-2">{{ $c->participant?->nombre ?? '—' }}</td>
            <td class="px-3 py-2">{{ $c->company?->nombre ?? '—' }}</td>
            <td class="px-3 py-2">{{ $c->offer?->puesto ? ('#'.$c->offer->id.' '.$c->offer->puesto) : '—' }}</td>
            <td class="px-3 py-2">{{ $c->fecha_inicio?->format('d/m/Y') ?? '—' }}</td>
            <td class="px-3 py-2">{{ $c->fecha_fin_prevista?->format('d/m/Y') ?? '—' }}</td>
            <td class="px-3 py-2">{{ $c->jornada_pct ? $c->jornada_pct.'%' : '—' }}</td>
            <td class="px-3 py-2">{{ $c->tipo_contrato ?? '—' }}</td>
            <td class="px-3 py-2">
              <div class="flex justify-end gap-2" x-data="{open:false}">
                <a href="{{ route('contracts.show', $c) }}"
                   class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 text-sm">Ver</a>
                <a href="{{ route('contracts.edit', $c) }}"
                   class="px-3 py-1.5 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 text-sm">Editar</a>

                <button type="button" @click="open=true"
                        class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 text-sm">
                  Borrar
                </button>

                {{-- Modal borrar --}}
                <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
                  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>
                  <div x-transition class="relative w-full max-w-md rounded-xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
                      <h3 class="text-lg font-semibold text-center text-red-600 dark:text-red-400">Confirmar eliminación</h3>
                      <p class="mt-2 text-sm text-center text-gray-600 dark:text-slate-300">
                        ¿Eliminar el contrato #{{ $c->id }}? Esta acción no se puede deshacer.
                      </p>
                    </div>
                    <div class="flex items-center justify-center gap-4 p-6">
                      <form method="POST" action="{{ route('contracts.destroy', $c) }}">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white hover:shadow dark:from-rose-500 dark:to-red-500">
                          Sí, eliminar
                        </button>
                      </form>
                      <button type="button" @click="open=false"
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
          <tr><td colspan="9" class="px-5 py-6 text-center text-gray-600 dark:text-slate-400">No hay contratos.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $contracts->links() }}
  </div>
@endsection

@section('scripts')
<script>
  // Buscador cliente (igual estilo al de participantes)
  (function(){
    const input = document.getElementById('searchInput');
    const clear = document.getElementById('clearButton');
    const rows  = () => document.querySelectorAll('#contractsTable tbody tr');

    function filter(){
      const q = (input.value || '').toLowerCase();
      rows().forEach(r => {
        r.style.display = q === '' ? '' : (r.textContent.toLowerCase().includes(q) ? '' : 'none');
      });
    }
    input?.addEventListener('input', filter);
    clear?.addEventListener('click', () => { input.value=''; filter(); });
  })();
</script>
@endsection
