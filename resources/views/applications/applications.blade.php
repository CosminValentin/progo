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
{{-- Estadísticas (cards estilo moderno) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
  {{-- Total candidaturas --}}
  <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 p-6 text-white shadow-lg hover:shadow-xl transition-all">
    <div class="flex items-center gap-4">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20">
        <i class="fa-solid fa-file-lines text-2xl"></i>
      </div>
      <div>
        <p class="text-sm opacity-80">Total candidaturas</p>
        <p class="text-3xl font-bold">{{ $total ?? $applications->total() }}</p>
      </div>
    </div>
  </div>

  {{-- Pendientes --}}
  <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 via-amber-600 to-yellow-600 p-6 text-white shadow-lg hover:shadow-xl transition-all">
    <div class="flex items-center gap-4">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20">
        <i class="fa-solid fa-clock text-2xl"></i>
      </div>
      <div>
        <p class="text-sm opacity-80">Pendientes</p>
        <p class="text-3xl font-bold">{{ $pendientes ?? $applications->where('estado','pendiente')->count() }}</p>
      </div>
    </div>
  </div>

  {{-- Aceptadas --}}
  <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 p-6 text-white shadow-lg hover:shadow-xl transition-all">
    <div class="flex items-center gap-4">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20">
        <i class="fa-solid fa-thumbs-up text-2xl"></i>
      </div>
      <div>
        <p class="text-sm opacity-80">Aceptadas</p>
        <p class="text-3xl font-bold">{{ $aceptadas ?? $applications->where('estado','aceptada')->count() }}</p>
      </div>
    </div>
  </div>

  {{-- Rechazadas --}}
  <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 via-rose-600 to-pink-600 p-6 text-white shadow-lg hover:shadow-xl transition-all">
    <div class="flex items-center gap-4">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-white/20">
        <i class="fa-solid fa-thumbs-down text-2xl"></i>
      </div>
      <div>
        <p class="text-sm opacity-80">Rechazadas</p>
        <p class="text-3xl font-bold">{{ $rechazadas ?? $applications->where('estado','rechazada')->count() }}</p>
      </div>
    </div>
  </div>
</div>


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
  <div class="flex justify-end gap-2" x-data="{ open:false }">
    <!-- Ver -->
    <a href="{{ route('applications.show', $a) }}"
       class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition flex items-center gap-1">
      <i class="fa-solid fa-eye"></i> Ver
    </a>
    <!-- Editar -->
    <a href="{{ route('applications.edit', $a) }}"
       class="px-3 py-2 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 transition flex items-center gap-1">
      <i class="fa-solid fa-pen-to-square"></i> Editar
    </a>
    <!-- Borrar -->
    <button @click="open = true"
            class="px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition flex items-center gap-1">
      <i class="fa-solid fa-trash"></i> Borrar
    </button>

    <!-- Modal de confirmación -->
    <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>
      <div x-transition class="relative w-full max-w-md rounded-xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
          <h3 class="text-lg font-semibold text-center text-red-600 dark:text-red-400">Confirmar eliminación</h3>
          <p class="mt-2 text-sm text-center text-gray-600 dark:text-slate-300">
            ¿Eliminar la candidatura de <strong>{{ $a->participant->nombre ?? '—' }}</strong> para <strong>{{ $a->offer->puesto ?? '—' }}</strong>? Esta acción no se puede deshacer.
          </p>
        </div>
        <div class="flex items-center justify-center gap-4 p-6">
          <form method="POST" action="{{ route('applications.destroy', $a) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white hover:shadow dark:from-rose-500 dark:to-red-500 flex items-center gap-1">
              <i class="fa-solid fa-trash"></i> Sí, eliminar
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
            <td colspan="6" class="px-5 py-6 text-center text-gray-500">No hay candidaturas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $applications->links() }}
  </div>

  @section('scripts')
<script>
// Función para manejar la búsqueda en la tabla sin enviar el formulario
function loadApplicationsSearcher() {
    const searchInput = document.querySelector('input[name="q"]'); // input de búsqueda
    const tableRows = document.querySelectorAll('table tbody tr');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();

        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const matches = Array.from(cells).some(cell =>
                cell.textContent.toLowerCase().includes(query)
            );

            if (matches) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}

// Inicializamos la función al cargar la página
document.addEventListener('DOMContentLoaded', loadApplicationsSearcher);
</script>
@endsection

@endsection
