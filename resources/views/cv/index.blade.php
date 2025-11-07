@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">CVs</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Listado y búsqueda rápida.</p>
    </div>

    <div class="flex flex-wrap gap-2">
      {{-- 🔹 Nuevo botón para generar CV automáticamente --}}
      <a href="{{ route('cv.generate') }}"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white hover:from-emerald-700 hover:to-teal-700 shadow">
        <i class="fa-solid fa-wand-magic-sparkles"></i> Generar CV
      </a>

      {{-- Botón ya existente para subir CV --}}
      <a href="{{ route('cvs.create') }}"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow">
        <i class="fa-solid fa-upload"></i> Subir CV
      </a>
    </div>
  </div>
@endsection

@section('content')
  {{-- Cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
    <div class="rounded-2xl p-6 text-white bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 shadow">
      <p class="text-sm opacity-80">Total CVs</p>
      <p class="text-3xl font-bold">{{ $total }}</p>
    </div>
    <div class="rounded-2xl p-6 text-white bg-gradient-to-br from-emerald-500 via-emerald-600 to-teal-600 shadow">
      <p class="text-sm opacity-80">Protegidos</p>
      <p class="text-3xl font-bold">{{ $tProtected }}</p>
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

  {{-- Buscador simple (cliente) --}}
  <div class="mb-6">
    <div class="relative max-w-3xl">
      <input id="cvSearchInput" type="text" value="{{ $q ?? '' }}"
             placeholder="Buscar por nombre del archivo, participante o hash…"
             class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm px-10 py-3 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-md transition-all duration-300 hover:shadow-lg">
      <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
      <button type="button" id="cvClearBtn"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 hover:underline">
        Limpiar
      </button>
    </div>
  </div>

  {{-- Tabla --}}
  <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl overflow-hidden">
    <table id="cvTable" class="min-w-full table-auto border-collapse">
      <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-slate-700/50 dark:to-slate-800 text-xs font-semibold uppercase text-indigo-700 dark:text-slate-200">
        <tr>
          <th class="px-4 py-3 text-left w-24">Fecha</th>
          <th class="px-4 py-3 text-left">Archivo</th>
          <th class="px-4 py-3 text-left w-56">Participante</th>
          <th class="px-4 py-3 text-left w-40">Subido por</th>
          <th class="px-4 py-3 text-center w-44">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
        @forelse($docs as $d)
          <tr class="hover:bg-indigo-50 dark:hover:bg-slate-700/30 transition">
            <td class="px-4 py-3 whitespace-nowrap">{{ optional($d->fecha)->format('d/m/Y') }}</td>
            <td class="px-4 py-3 truncate" title="{{ $d->nombre_archivo }}">
              <div class="flex items-center gap-2">
                <i class="fa-regular fa-file-lines text-indigo-600 dark:text-indigo-300"></i>
                <span class="truncate">{{ $d->nombre_archivo }}</span>
              </div>
              <div class="text-xs text-gray-500 dark:text-slate-400">#{{ $d->id }} — {{ $d->hash }}</div>
            </td>
            <td class="px-4 py-3">
              @if($d->owner)
                <div class="flex items-center gap-2">
                  <i class="fa-regular fa-id-badge text-emerald-600 dark:text-emerald-300"></i>
                  <span>#{{ $d->owner->id }} — {{ $d->owner->nombre }}</span>
                </div>
              @else
                —
              @endif
            </td>
            <td class="px-4 py-3">{{ $d->uploader?->name ?? '—' }}</td>
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2" x-data="{open:false}">
                <a href="{{ route('cvs.download', $d) }}"
                   class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition text-sm">
                  Descargar
                </a>
                <a href="{{ route('cvs.edit', $d) }}"
                   class="px-3 py-1.5 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 transition text-sm">
                  Editar
                </a>

                <button @click="open=true"
                        class="px-3 py-1.5 rounded-lg {{ $d->protegido ? 'cursor-not-allowed opacity-60 bg-red-100/60 text-red-400' : 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50' }} transition text-sm"
                        {{ $d->protegido ? 'disabled' : '' }}>
                  Borrar
                </button>

                {{-- Modal borrar --}}
                <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
                  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>
                  <div x-transition class="relative w-full max-w-md rounded-xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
                      <h3 class="text-lg font-semibold text-center text-red-600 dark:text-red-400">Confirmar eliminación</h3>
                      <p class="mt-2 text-sm text-center text-gray-600 dark:text-slate-300">
                        ¿Eliminar el CV <strong>{{ $d->nombre_archivo }}</strong>? Esta acción no se puede deshacer.
                      </p>
                    </div>
                    <div class="flex items-center justify-center gap-4 p-6">
                      <form method="POST" action="{{ route('cvs.destroy', $d) }}">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white hover:shadow dark:from-rose-500 dark:to-red-500">
                          Sí, eliminar
                        </button>
                      </form>
                      <button @click="open=false" class="px-6 py-2 text-gray-700 dark:text-slate-200 bg-gray-100 hover:bg-gray-200 dark:bg-slate-600 dark:hover:bg-slate-700 rounded-lg">Cancelar</button>
                    </div>
                  </div>
                </div>
                {{-- /Modal --}}
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-5 py-6 text-center text-gray-600 dark:text-slate-400">No hay CVs.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $docs->links() }}
  </div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('cvSearchInput');
    const clear = document.getElementById('cvClearBtn');
    const rows  = document.querySelectorAll('#cvTable tbody tr');

    function filter(){
      const q = (input.value || '').toLowerCase();
      rows.forEach(r => {
        let txt = r.textContent.toLowerCase();
        r.style.display = txt.includes(q) ? '' : 'none';
      });
    }

    input.addEventListener('input', filter);
    clear.addEventListener('click', () => { input.value=''; filter(); });
  });
</script>
@endsection
