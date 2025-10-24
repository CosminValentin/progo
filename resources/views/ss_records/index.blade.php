@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Registros SS</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Altas, jornadas y coeficientes por participante.</p>
    </div>
    <a href="{{ route('ss.create') }}"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow-lg transition">
      <i class="fa-solid fa-plus"></i> Nuevo registro
    </a>
  </div>
@endsection

@section('content')
  {{-- Cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 p-6 text-white shadow-lg hover:shadow-xl transition-all">
      <div class="flex items-center gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20"><i class="fa-solid fa-database text-2xl"></i></div>
        <div><p class="text-sm opacity-80">Total registros</p><p class="text-3xl font-bold">{{ $totalRegistros }}</p></div>
      </div>
    </div>
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 p-6 text-white shadow-lg hover:shadow-xl transition-all">
      <div class="flex items-center gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20"><i class="fa-solid fa-user-check text-2xl"></i></div>
        <div><p class="text-sm opacity-80">Regímenes "alta"</p><p class="text-3xl font-bold">{{ $conRegimenAlta }}</p></div>
      </div>
    </div>
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 p-6 text-white shadow-lg hover:shadow-xl transition-all">
      <div class="flex items-center gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20"><i class="fa-solid fa-calendar-day text-2xl"></i></div>
        <div><p class="text-sm opacity-80">Suma días alta</p><p class="text-3xl font-bold">{{ $sumDiasAlta }}</p></div>
      </div>
    </div>
    <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 via-pink-600 to-red-600 p-6 text-white shadow-lg hover:shadow-xl transition-all">
      <div class="flex items-center gap-4">
        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20"><i class="fa-solid fa-hourglass-half text-2xl"></i></div>
        <div><p class="text-sm opacity-80">Suma jornadas</p><p class="text-3xl font-bold">{{ $sumJornadas }}</p></div>
      </div>
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

  {{-- Buscador --}}
  <form id="searchForm" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4" onsubmit="return false;">
    <div class="col-span-2">
      <div class="relative">
        <input type="text" id="searchInput" name="q" value="{{ $q ?? '' }}" placeholder="Buscar por participante, DNI/NIE, régimen u observaciones…"
               class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm px-10 py-3 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-md transition-all duration-300 hover:shadow-lg">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
      </div>
    </div>
    <div class="flex gap-3">
      <button type="button" id="searchButton" class="flex-1 px-5 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow">Buscar</button>
      <button type="button" id="clearButton" class="flex-1 px-5 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700">Limpiar</button>
    </div>
  </form>

  {{-- Tabla --}}
  <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl overflow-auto">
    <table id="ssTable" class="min-w-full table-auto border-collapse">
      <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-slate-700/50 dark:to-slate-800 text-xs font-semibold uppercase text-indigo-700 dark:text-slate-200">
        <tr>
          <th class="px-4 py-3 w-16 text-left">#</th>
          <th class="px-4 py-3 w-48 text-left">Participante</th>
          <th class="px-4 py-3 w-32 text-left">Régimen</th>
          <th class="px-4 py-3 w-28 text-left">Días alta</th>
          <th class="px-4 py-3 w-28 text-left">Jornadas</th>
          <th class="px-4 py-3 w-28 text-left">Coef.</th>
          <th class="px-4 py-3 w-32 text-left">Días eq.</th>
          <th class="px-4 py-3 w-40 text-left">Observaciones</th>
          <th class="px-4 py-3 w-36 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
        @forelse($records as $ss)
          <tr class="hover:bg-indigo-50 dark:hover:bg-slate-700/30 transition">
            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">{{ $ss->id }}</td>
            <td class="px-4 py-3">
              <div class="text-gray-800 dark:text-slate-100 truncate max-w-[220px]" title="{{ $ss->participant->nombre ?? '—' }}">
                {{ $ss->participant->nombre ?? '—' }}
              </div>
              <div class="text-xs text-gray-500 dark:text-slate-400">{{ $ss->participant?->dni_nie }}</div>
            </td>
            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">{{ $ss->regimen }}</td>
            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">{{ $ss->dias_alta ?? '—' }}</td>
            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">{{ $ss->jornadas_reales ?? '—' }}</td>
            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">{{ $ss->coef_aplicado ?? '—' }}</td>
            <td class="px-4 py-3 text-gray-700 dark:text-slate-300">{{ $ss->dias_equivalentes ?? '—' }}</td>
            <td class="px-4 py-3 text-gray-600 dark:text-slate-400 truncate max-w-[280px]" title="{{ $ss->observaciones }}">
              {{ \Illuminate\Support\Str::limit($ss->observaciones, 80) }}
            </td>
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2" x-data="{open:false}">
                <a href="{{ route('ss.show', $ss) }}" class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 text-sm">Ver</a>
                <a href="{{ route('ss.edit', $ss) }}" class="px-3 py-1.5 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 text-sm">Editar</a>
                <button @click="open=true" class="px-3 py-1.5 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 text-sm">Borrar</button>

                <!-- Modal -->
                <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
                  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>
                  <div x-transition class="relative w-full max-w-md rounded-xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
                      <h3 class="text-lg font-semibold text-center text-red-600 dark:text-red-400">Confirmar eliminación</h3>
                      <p class="mt-2 text-sm text-center text-gray-600 dark:text-slate-300">¿Eliminar este registro SS? Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="flex items-center justify-center gap-4 p-6">
                      <form method="POST" action="{{ route('ss.destroy', $ss) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white hover:shadow">
                          Sí, eliminar
                        </button>
                      </form>
                      <button @click="open=false" class="px-6 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-600 dark:hover:bg-slate-700 rounded-lg">
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
            <td colspan="9" class="px-5 py-6 text-center text-gray-600 dark:text-slate-400">No hay registros SS.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Paginación --}}
  <div class="mt-6">
    {{ $records->links() }}
  </div>
@endsection

@section('scripts')
<script>
  function filterTable() {
    const q = (document.querySelector('#searchInput')?.value || '').toLowerCase();
    const rows = document.querySelectorAll('#ssTable tbody tr');
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = q === '' ? '' : (text.includes(q) ? '' : 'none');
    });
  }
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('#searchInput')?.addEventListener('input', filterTable);
    document.querySelector('#searchButton')?.addEventListener('click', filterTable);
    document.querySelector('#clearButton')?.addEventListener('click', () => { const i = document.querySelector('#searchInput'); if (i) { i.value=''; filterTable(); }});
  });
</script>
@endsection
