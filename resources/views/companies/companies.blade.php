@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-2xl font-bold">Empresas</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">Gestiona altas, ediciones y bajas de empresas.</p>
    </div>
    <a href="{{ route('addcompany') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold shadow hover:shadow-lg hover:scale-[1.02] active:scale-100 transition-all">
      <i class="fa-solid fa-building-circle-plus opacity-90"></i>
      Nueva empresa
    </a>
  </div>
@endsection

@section('content')
  @if(session('success'))
    <div class="mb-6 rounded-xl border border-emerald-200/70 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
      <div class="flex items-start gap-3">
        <i class="fa-solid fa-circle-check mt-0.5 text-emerald-600"></i>
        <div class="flex-1 text-sm">{{ session('success') }}</div>
      </div>
    </div>
  @endif

  <form method="GET" action="{{ route('companies') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
    <div class="col-span-2">
      <label class="sr-only">Buscar</label>
      <div class="relative">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Buscar por CIF/NIF, nombre, actividad o email…"
               class="w-full rounded-xl border border-gray-200/80 bg-white/80 backdrop-blur px-4 py-2.5 pr-10 shadow-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
        <i class="fa-solid fa-magnifying-glass absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
      </div>
    </div>
    <div class="flex gap-2">
      <button class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 shadow-sm hover:shadow transition">
        Buscar
      </button>
      <a href="{{ route('companies') }}" class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 shadow-sm hover:shadow transition">
        Limpiar
      </a>
    </div>
  </form>

  <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white dark:bg-slate-800 shadow-lg">
    <table class="min-w-full">
      <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-slate-700 dark:to-slate-700 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-slate-300">
        <tr>
          <th class="px-5 py-3">CIF/NIF</th>
          <th class="px-5 py-3">Nombre</th>
          <th class="px-5 py-3 hidden md:table-cell">Actividad</th>
          <th class="px-5 py-3 hidden lg:table-cell">Contacto</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse ($companies as $c)
          <tr class="odd:bg-white even:bg-gray-50/60 dark:odd:bg-slate-800 dark:even:bg-slate-800/60 hover:bg-indigo-50/50 dark:hover:bg-slate-700/40 transition">
            <td class="px-5 py-3 text-sm font-semibold text-gray-800 dark:text-slate-100">{{ $c->cif_nif }}</td>
            <td class="px-5 py-3 text-sm text-gray-700 dark:text-slate-200">{{ $c->nombre }}</td>
            <td class="px-5 py-3 text-sm text-gray-600 dark:text-slate-300 hidden md:table-cell">{{ $c->actividad ?: '—' }}</td>
            <td class="px-5 py-3 text-sm text-gray-600 dark:text-slate-300 hidden lg:table-cell">
              {{ $c->contacto_nombre ?: '—' }} · {{ $c->contacto_email ?: '—' }}
            </td>
            <td class="px-5 py-3 text-sm">
              <div class="flex justify-end flex-wrap gap-2" x-data="{ open:false }">
                <a href="{{ route('viewcompany', $c) }}" class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 border border-gray-200 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-700 hover:shadow transition">Ver</a>
                <a href="{{ route('editcompany', $c) }}" class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 border border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:shadow transition">Editar</a>

                <!-- Modal borrar -->
                <button @click="open = true" class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:shadow transition">
                  Borrar
                </button>
                <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
                  <div class="absolute inset-0 bg-black/40" @click="open=false"></div>
                  <div x-transition class="relative w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/5">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
                      <h3 class="text-lg font-semibold text-center text-gray-800 dark:text-white">Confirmar eliminación</h3>
                      <p class="mt-1 text-sm text-center text-gray-500 dark:text-slate-300">
                        ¿Eliminar <strong>{{ $c->nombre }}</strong> (CIF/NIF: {{ $c->cif_nif }})?
                      </p>
                    </div>
                    <div class="px-6 py-5 flex flex-col sm:flex-row sm:justify-between gap-3">
                      <button @click="open=false" class="px-4 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 shadow-sm transition">Cancelar</button>
                      <form method="POST" action="{{ route('deletecompany', $c) }}">
                        @csrf
                        <button class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white font-semibold shadow hover:shadow-lg transition">Sí, eliminar</button>
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
            <td colspan="5" class="px-5 py-12 text-center text-gray-500 dark:text-slate-400">
              No hay empresas todavía.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    <div class="inline-block rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 shadow-sm">
      {{ $companies->onEachSide(1)->links('pagination::tailwind') }}
    </div>
  </div>
@endsection
