@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Documentos</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Listado y búsqueda rápida.</p>
    </div>

    <a href="{{ route('documents.create') }}"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow">
      <i class="fa-solid fa-upload"></i> Subir documentos
    </a>
  </div>
@endsection

@section('content')
  {{-- Cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
    <div class="rounded-2xl p-6 text-white bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 shadow">
      <p class="text-sm opacity-80">Total documentos</p>
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

  {{-- 🔍 Buscador instantáneo (client-side, como en participantes) --}}
  <form id="docsSearchForm" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4" onsubmit="return false;">
    <div class="col-span-2">
      <div class="relative">
        <input type="text" id="docsSearchInput" name="q"
               placeholder="Buscar por nombre, tipo, propietario o usuario…"
               class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm px-10 py-3 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-md transition-all duration-300 hover:shadow-lg">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
      </div>
    </div>
    <div class="flex gap-3">
      <button type="button" id="docsSearchButton"
              class="flex-1 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-indigo-700 text-white hover:from-indigo-700 hover:to-indigo-800 transition-all duration-300 shadow-md focus:ring-2 focus:ring-indigo-500">
        Buscar
      </button>
      <button type="button" id="docsClearButton"
              class="flex-1 px-5 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-all duration-300 shadow-md focus:ring-2 focus:ring-indigo-500">
        Limpiar
      </button>
    </div>
  </form>

  {{-- Tabla --}}
  <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl overflow-auto">
    <table id="documentsTable" class="min-w-full table-auto border-collapse">
      <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-slate-700/50 dark:to-slate-800 text-xs font-semibold uppercase text-indigo-700 dark:text-slate-200">
        <tr>
          <th class="px-4 py-3 text-left w-28">Fecha</th>
          <th class="px-4 py-3 text-left">Nombre</th>
          <th class="px-4 py-3 text-left w-32">Tipo</th>
          <th class="px-4 py-3 text-left w-40">Propietario</th>
          <th class="px-4 py-3 text-left w-40">Subido por</th>
          <th class="px-4 py-3 text-center w-44">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
        @forelse($docs as $d)
          <tr class="hover:bg-indigo-50 dark:hover:bg-slate-700/30 transition">
            <td class="px-4 py-3 whitespace-nowrap">{{ optional($d->fecha)->format('d/m/Y') }}</td>
            <td class="px-4 py-3 truncate max-w-[360px]" title="{{ $d->nombre_archivo }}">{{ $d->nombre_archivo }}</td>
            <td class="px-4 py-3">{{ $d->tipo ?? '—' }}</td>
            <td class="px-4 py-3">
              @php
                $ownerLabel = '—';
                if ($d->owner) {
                  if ($d->owner_type === 'participants') $ownerLabel = 'Participante: ' . ($d->owner->nombre ?? $d->owner->id);
                  elseif ($d->owner_type === 'companies') $ownerLabel = 'Empresa: ' . ($d->owner->nombre ?? $d->owner->id);
                  elseif ($d->owner_type === 'offers') $ownerLabel = 'Oferta: #' . $d->owner->id;
                  elseif ($d->owner_type === 'users') $ownerLabel = 'Usuario: ' . ($d->owner->name ?? $d->owner->id);
                }
              @endphp
              {{ $ownerLabel }}
            </td>
            <td class="px-4 py-3">{{ $d->uploader?->name ?? '—' }}</td>
            <td class="px-4 py-3">
              <div class="flex justify-center gap-2">
                <a href="{{ route('documents.download', $d) }}"
                   class="px-3 py-1.5 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition text-sm">
                  Descargar
                </a>
                <form method="POST" action="{{ route('documents.destroy', $d) }}"
                      onsubmit="return confirm('¿Eliminar el documento?');">
                  @csrf @method('DELETE')
                  <button
                    class="px-3 py-1.5 rounded-lg {{ $d->protegido ? 'cursor-not-allowed opacity-60 bg-red-100/60 text-red-400' : 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50' }} transition text-sm"
                    {{ $d->protegido ? 'disabled' : '' }}>
                    Borrar
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-5 py-6 text-center text-gray-600 dark:text-slate-400">No hay documentos.</td>
          </tr>
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
  // --- Buscador instantáneo (cliente) ---
  function initDocsSearchForm() {
    const form = document.querySelector('#docsSearchForm');
    form?.addEventListener('submit', e => e.preventDefault());
  }

  function initDocsSearchInput() {
    const input   = document.querySelector('#docsSearchInput');
    const button  = document.querySelector('#docsSearchButton');
    const clear   = document.querySelector('#docsClearButton');
    const rows    = () => document.querySelectorAll('#documentsTable tbody tr');

    const filter = () => {
      const q = (input?.value || '').toLowerCase().trim();
      rows().forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = q === '' ? '' : (text.includes(q) ? '' : 'none');
      });
    };

    input?.addEventListener('input', filter);
    button?.addEventListener('click', filter);
    clear?.addEventListener('click', () => { input.value = ''; filter(); });
  }

  document.addEventListener('DOMContentLoaded', () => {
    initDocsSearchForm();
    initDocsSearchInput();
  });
</script>
@endsection
