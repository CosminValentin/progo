@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Candidaturas</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Gestiona altas, ediciones y bajas.</p>
    </div>
    <a href="{{ route('addapplication') }}"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow-lg transition">
      <i class="fa-solid fa-plus"></i> Nueva
    </a>
  </div>
@endsection

@section('content')
  <!-- Mensaje de éxito -->
  @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-600 dark:bg-emerald-900 dark:text-emerald-100 shadow">
      {{ session('success') }}
    </div>
  @endif

  <!-- Mensaje de error -->
  @if(session('error'))
    <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 p-4 text-rose-800 dark:border-rose-600 dark:bg-rose-900 dark:text-rose-100 shadow">
      {{ session('error') }}
    </div>
  @endif

  <!-- Estadísticas -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total de candidaturas -->
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-slate-700/50 dark:to-slate-800 p-5 shadow-lg hover:shadow-2xl transition">
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-users text-indigo-600 dark:text-indigo-300 text-xl"></i>
        <div class="text-sm text-gray-500 dark:text-slate-400">Total</div>
      </div>
      <div class="mt-3 flex items-baseline gap-2">
        <div class="text-3xl font-semibold text-gray-800 dark:text-slate-100">{{ number_format($applications->total()) }}</div>
        <span class="text-xs px-3 py-1 rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200">live</span>
      </div>
    </div>

    <!-- Página actual -->
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-slate-700/50 dark:to-slate-800 p-5 shadow-lg hover:shadow-2xl transition">
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-file-alt text-blue-600 dark:text-blue-300 text-xl"></i>
        <div class="text-sm text-gray-500 dark:text-slate-400">Página</div>
      </div>
      <div class="mt-3 text-3xl font-semibold text-gray-800 dark:text-slate-100">{{ $applications->currentPage() }}/{{ $applications->lastPage() }}</div>
    </div>

    <!-- Candidaturas mostradas -->
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gradient-to-r from-green-50 to-green-100 dark:from-slate-700/50 dark:to-slate-800 p-5 shadow-lg hover:shadow-2xl transition">
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-eye text-green-600 dark:text-green-300 text-xl"></i>
        <div class="text-sm text-gray-500 dark:text-slate-400">Mostrando</div>
      </div>
      <div class="mt-3 text-3xl font-semibold text-gray-800 dark:text-slate-100">{{ $applications->count() }}</div>
    </div>

    <!-- Estado de las candidaturas -->
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gradient-to-r from-purple-50 to-purple-100 dark:from-slate-700/50 dark:to-slate-800 p-5 shadow-lg hover:shadow-2xl transition">
      <div class="flex items-center gap-3">
        <i class="fa-solid fa-circle-check text-purple-600 dark:text-purple-300 text-xl"></i>
        <div class="text-sm text-gray-500 dark:text-slate-400">Estado</div>
      </div>
      <div class="mt-3 flex items-center gap-2">
        <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-300 text-sm">
          <i class="fa-solid fa-circle text-[8px]"></i> Activo
        </span>
      </div>
    </div>
  </div>

  <!-- Buscador -->
  <form id="searchForm" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4" onsubmit="return false;">
    <div class="col-span-2">
      <div class="relative">
        <input type="text" id="searchInput" name="q" value="{{ $q ?? '' }}" placeholder="Buscar por CIF/NIF, nombre o email…" 
               class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm px-10 py-3 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-md transition-all duration-300 hover:shadow-lg">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
      </div>
    </div>
    <div class="flex gap-3">
      <button type="button" id="searchButton" class="flex-1 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 text-white hover:from-indigo-700 hover:to-indigo-800 transition-all duration-300 shadow-md focus:ring-2 focus:ring-indigo-500">Buscar</button>
      <button type="button" id="clearButton" class="flex-1 px-5 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all duration-300 shadow-md focus:ring-2 focus:ring-indigo-500">Limpiar</button>
    </div>
  </form>

  <!-- Tabla de candidaturas -->
<div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl">
  <table id="applicationsTable" class="min-w-full">
    <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-slate-700/50 dark:to-slate-800 text-left text-xs font-semibold uppercase tracking-wide text-indigo-700 dark:text-slate-200">
      <tr>
        <th class="px-5 py-3">Fecha</th>
        <th class="px-5 py-3">Participante</th>
        <th class="px-5 py-3 hidden md:table-cell">Oferta</th>
        <th class="px-5 py-3">Estado</th>
        <th class="px-5 py-3 text-right">Acciones</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
      @forelse($applications as $a)
        <tr class="hover:bg-indigo-50 dark:hover:bg-slate-700/30 transition">
          <td class="px-5 py-4 font-medium text-gray-800 dark:text-slate-100">{{ optional($a->fecha)->format('d/m/Y') }}</td>
          <td class="px-5 py-4 text-gray-700 dark:text-slate-300">
            {{ $a->participant->nombre ?? '—' }}
            <span class="text-xs text-gray-500 dark:text-slate-400">({{ $a->participant->dni_nie ?? '' }})</span>
          </td>
          <td class="px-5 py-4 text-gray-600 dark:text-slate-400 hidden md:table-cell">
            {{ ($a->offer->titulo ?? $a->offer->nombre) . ($a->offer->puesto ?? 'Sin puesto') }}
          </td>
          <td class="px-5 py-4 text-gray-600 dark:text-slate-400">{{ ucfirst($a->estado) }}</td>
          <td class="px-5 py-4">
            <div class="flex justify-end gap-2" x-data="{open:false}">
              <!-- Botones de acción -->
              <a href="{{ route('viewapplication', $a) }}"
                 class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition">Ver</a>
              <a href="{{ route('editapplication', $a) }}"
                 class="px-3 py-2 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 transition">Editar</a>
              <button @click="open=true"
                      class="px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition">Borrar</button>

              <!-- Modal de confirmación -->
              <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>
                <div x-transition class="relative w-full max-w-md rounded-xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
                  <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-center text-red-600 dark:text-red-400">Confirmar eliminación</h3>
                    <p class="mt-2 text-sm text-center text-gray-600 dark:text-slate-300">
                      ¿Eliminar <strong>{{ $a->participant->nombre ?? 'participante' }}</strong> de la candidatura? Esta acción no se puede deshacer.
                    </p>
                  </div>
                  <div class="flex items-center justify-center gap-4 p-6">
                    <form method="POST" action="{{ route('deleteapplication', $a) }}">
                      @csrf
                      <button class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white hover:shadow dark:from-rose-500 dark:to-red-500">Sí, eliminar</button>
                    </form>
                    <button @click="open=false" class="px-6 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-600 dark:hover:bg-slate-700 rounded-lg">Cancelar</button>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="px-5 py-4 text-center text-gray-600 dark:text-slate-400">No se encontraron candidaturas.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

  <!-- Paginación -->
  <div class="mt-6">
    {{ $applications->links() }}
  </div>
@endsection

@section('scripts')
  <script>
    // Función para manejar el formulario de búsqueda
    function initializeSearchForm() {
      let form = document.body.querySelector('#searchForm');
      form.addEventListener('submit', preventFormSubmit);
    }

    // Función para prevenir el envío del formulario al presionar Enter
    function preventFormSubmit(event) {
      event.preventDefault();
    }

    // Función para manejar la búsqueda dentro de la tabla
    function initializeSearchInput() {
      let searchInput = document.body.querySelector('#searchInput');
      searchInput.addEventListener('input', filterTableRows);
    }

    // Función para filtrar las filas de la tabla según el texto ingresado
    function filterTableRows() {
      let query = this.value.toLowerCase();
      let rows = document.body.querySelectorAll('#applicationsTable tbody tr');
      
      for (let row of rows) {
        let cells = row.querySelectorAll('td');
        let matches = false;

        // Verificamos si alguna celda de la fila contiene el texto buscado
        for (let cell of cells) {
          if (cell.textContent.toLowerCase().includes(query)) {
            matches = true;
            break; // Si encontramos una coincidencia, ya no seguimos buscando
          }
        }

        // Mostrar u ocultar la fila dependiendo de si hay coincidencias
        if (matches) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      }
    }

    // Función para inicializar todo
    function initializeSearch() {
      initializeSearchForm();
      initializeSearchInput();
    }

    // Inicializamos la búsqueda cuando el contenido de la página esté cargado
    document.addEventListener('DOMContentLoaded', function() {
      initializeSearch();
    });
  </script>
@endsection
