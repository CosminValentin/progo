@extends('layouts.app_windmill')

@section('header')
  <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Ofertas</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Gestiona altas, ediciones y bajas.</p>
    </div>
    <a href="{{ route('addoffer') }}"
       class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow-lg transition">
      <i class="fa-solid fa-plus"></i> Nueva
    </a>
  </div>
@endsection

@section('content')
  @if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-600 dark:bg-emerald-900 dark:text-emerald-100 shadow">
      {{ session('success') }}
    </div>
  @endif

  <!-- Estadísticas -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow">
      <div class="text-sm text-gray-500 dark:text-slate-400">Total</div>
      <div class="mt-1 flex items-baseline gap-2">
        <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100">{{ number_format($offers->total()) }}</div>
        <span class="text-xs px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200">live</span>
      </div>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow">
      <div class="text-sm text-gray-500 dark:text-slate-400">Página</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800 dark:text-slate-100">{{ $offers->currentPage() }}/{{ $offers->lastPage() }}</div>
    </div>
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow">
      <div class="text-sm text-gray-500 dark:text-slate-400">Mostrando</div>
      <div class="mt-1 text-2xl font-semibold text-gray-800 dark:text-slate-100">{{ $offers->count() }}</div>
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

  <!-- Buscador -->
  <form method="GET" action="{{ route('offers') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="col-span-2">
      <div class="relative">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Buscar por puesto, empresa, ubicación o estado…"
               class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm px-10 py-3 placeholder-gray-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 shadow-sm transition">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
      </div>
    </div>
    <div class="flex gap-3">
      <button class="flex-1 px-5 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition shadow">Buscar</button>
      <a href="{{ route('offers') }}"
         class="flex-1 px-5 py-3 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition shadow">Limpiar</a>
    </div>
  </form>

  <!-- Tabla -->
  <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-xl">
    <table class="min-w-full">
      <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-slate-700/50 dark:to-slate-800 text-left text-xs font-semibold uppercase tracking-wide text-indigo-700 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3">Fecha</th>
          <th class="px-5 py-3">Puesto</th>
          <th class="px-5 py-3 hidden md:table-cell">Empresa</th>
          <th class="px-5 py-3 hidden lg:table-cell">Ubicación</th>
          <th class="px-5 py-3">Estado</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
        @forelse($offers as $o)
          <tr class="hover:bg-indigo-50 dark:hover:bg-slate-700/30 transition">
            <td class="px-5 py-4 font-medium text-gray-800 dark:text-slate-100">{{ optional($o->fecha)->format('d/m/Y') }}</td>
            <td class="px-5 py-4 text-gray-700 dark:text-slate-300">{{ $o->puesto }}</td>
            <td class="px-5 py-4 text-gray-600 dark:text-slate-400 hidden md:table-cell">{{ $o->company->nombre ?? '—' }}</td>
            <td class="px-5 py-4 text-gray-600 dark:text-slate-400 hidden lg:table-cell">{{ $o->ubicacion ?: '—' }}</td>
            <td class="px-5 py-4">
              <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs {{ $o->estado_badge_classes }}">
                {{ ucfirst(str_replace('_',' ',$o->estado ?? '')) }}
              </span>
            </td>
            <td class="px-5 py-4">
              <div class="flex justify-end gap-2" x-data="{open:false}">
                <a href="{{ route('viewoffer', $o) }}" class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition">Ver</a>
                <a href="{{ route('editoffer', $o) }}" class="px-3 py-2 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:hover:bg-yellow-900/50 transition">Editar</a>
                <button @click="open=true" class="px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition">Borrar</button>

                <!-- Modal -->
                <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
                  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>
                  <div x-transition class="relative w-full max-w-md rounded-xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/10 dark:ring-white/10">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
                      <h3 class="text-lg font-semibold text-center text-red-600 dark:text-red-400">Confirmar eliminación</h3>
                      <p class="mt-2 text-sm text-center text-gray-600 dark:text-slate-300">¿Eliminar la oferta <strong>{{ $o->puesto }}</strong>?</p>
                    </div>
                    <div class="flex items-center justify-center gap-4 p-6">
                      <form method="POST" action="{{ route('deleteoffer', $o) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg">Eliminar</button>
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
            <td colspan="6" class="px-5 py-4 text-center text-gray-600 dark:text-slate-400">No se encontraron ofertas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $offers->links() }}
  </div>
@endsection
