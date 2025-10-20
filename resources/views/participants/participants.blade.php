@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10 rounded-2xl bg-gradient-to-br from-indigo-50 via-white to-sky-50 shadow-xl" x-data="{ showAlert: true }">

  <!-- Header -->
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 border-b pb-5">
    <div>
      <h1 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-700 to-sky-600 bg-clip-text text-transparent">
        Participantes
      </h1>
      <p class="text-sm text-indigo-500 mt-1">Gestiona altas, ediciones y bajas de participantes.</p>
    </div>
    <a href="{{ route('addparticipant') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold shadow hover:shadow-lg hover:scale-[1.02] active:scale-100 transition-all">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
      Nuevo
    </a>
  </div>

  <!-- Alert -->
  @if(session('success'))
    <div x-show="showAlert"
         class="mb-6 rounded-xl border border-emerald-200/70 bg-emerald-50 p-4 text-emerald-800 shadow-sm">
      <div class="flex items-start gap-3">
        <svg class="h-5 w-5 mt-0.5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <div class="flex-1">
          <strong class="block">Operación correcta</strong>
          <span class="text-sm">{{ session('success') }}</span>
        </div>
        <button class="rounded-full px-2 text-emerald-900/70 hover:text-emerald-900" @click="showAlert=false">✕</button>
      </div>
    </div>
  @endif

  <!-- Filtros -->
  <form method="GET" action="{{ route('participants') }}"
        class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
    <div class="col-span-2">
      <label class="sr-only">Buscar</label>
      <div class="relative">
        <input type="text" name="q" value="{{ $q ?? '' }}"
               placeholder="Buscar por DNI/NIE, nombre o email…"
               class="w-full rounded-xl border border-gray-200/80 bg-white/80 backdrop-blur px-4 py-2.5 pr-10 shadow-sm focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition">
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/>
        </svg>
      </div>
    </div>
    <div class="flex gap-2">
      <button
        class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 shadow-sm hover:shadow transition">
        Buscar
      </button>
      <a href="{{ route('participants') }}"
         class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 shadow-sm hover:shadow transition">
        Limpiar
      </a>
    </div>
  </form>

  <!-- Tabla -->
  <div class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-lg">
    <table class="min-w-full">
      <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
        <tr>
          <th class="px-5 py-3">DNI/NIE</th>
          <th class="px-5 py-3">Nombre</th>
          <th class="px-5 py-3 hidden md:table-cell">Email</th>
          <th class="px-5 py-3 hidden lg:table-cell">Provincia</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse ($participants as $p)
          <tr class="odd:bg-white even:bg-gray-50/60 hover:bg-indigo-50/50 transition">
            <td class="px-5 py-3 text-sm font-semibold text-gray-800">{{ $p->dni_nie }}</td>
            <td class="px-5 py-3 text-sm text-gray-700">{{ $p->nombre }}</td>
            <td class="px-5 py-3 text-sm text-gray-600 hidden md:table-cell">{{ $p->email }}</td>
            <td class="px-5 py-3 text-sm text-gray-600 hidden lg:table-cell">
              <span class="inline-flex items-center gap-2">
                {{ $p->provincia }}
                @if($p->estado)
                  <span class="ml-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold tracking-wide shadow
                               {{ $p->estado==='activo'
                                  ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                                  : 'bg-amber-100 text-amber-700 border border-amber-200' }}">
                    {{ ucfirst($p->estado) }}
                  </span>
                @endif
              </span>
            </td>
            <td class="px-5 py-3 text-sm">
              <div class="flex justify-end flex-wrap gap-2">
                <a href="{{ route('viewparticipant', $p) }}"
                   class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:shadow transition">
                  Ver
                </a>
                <a href="{{ route('editparticipant', $p) }}"
                   class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 border border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:shadow transition">
                  Editar
                </a>
                <form action="{{ route('deleteparticipant', $p) }}" method="POST"
                      x-data
                      @submit.prevent="if(confirm('¿Eliminar a {{ $p->nombre }}?')) $el.submit()">
                  @csrf
                  <button
                    class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:shadow transition">
                    Borrar
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-5 py-12 text-center">
              <div class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-500 shadow-sm">
                <svg class="h-4 w-4 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M9 17v-6a2 2 0 114 0v6m-6 4h8a2 2 0 002-2v-5a8 8 0 10-12 0v5a2 2 0 002 2z"/></svg>
                No hay participantes todavía.
              </div>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Paginación -->
  <div class="mt-8">
    <div class="inline-block rounded-xl border border-gray-200 bg-white px-3 py-2 shadow-sm">
      {{ $participants->links() }}
    </div>
  </div>
</div>
@endsection
