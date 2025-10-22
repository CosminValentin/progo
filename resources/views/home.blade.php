@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-3xl font-semibold text-gray-900 dark:text-white">Inicio</h1>
      <p class="text-lg text-gray-500 dark:text-slate-400">
        Bienvenido/a, {{ Auth::user()->name }}. Resumen de actividad y accesos directos.
      </p>
    </div>
  </div>
@endsection

@section('content')
  {{-- KPIs / Accesos rápidos --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Tarjeta Participantes -->
    <a href="{{ url('/participants') }}" class="group relative rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-700 text-white p-5 shadow-xl transition-transform transform hover:scale-105 hover:shadow-2xl">
      <div class="flex items-center justify-between mb-3">
        <span class="text-sm">Participantes</span>
        <span class="inline-flex items-center px-2 py-0.5 text-[11px] bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200">CRUD</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="text-4xl font-semibold">{{ number_format($stats['participants'] ?? 0) }}</div>
        <i class="fa-solid fa-users text-white opacity-75 group-hover:opacity-100 transition"></i>
      </div>
      <p class="mt-1 text-xs">Total registrados</p>
    </a>

    <!-- Tarjeta Empresas -->
    <a href="{{ url('/companies') }}" class="group relative rounded-xl bg-gradient-to-r from-green-500 to-green-700 text-white p-5 shadow-xl transition-transform transform hover:scale-105 hover:shadow-2xl">
      <div class="flex items-center justify-between mb-3">
        <span class="text-sm">Empresas</span>
        <span class="inline-flex items-center px-2 py-0.5 text-[11px] bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-200">CRUD</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="text-4xl font-semibold">{{ number_format($stats['companies'] ?? 0) }}</div>
        <i class="fa-solid fa-building text-white opacity-75 group-hover:opacity-100 transition"></i>
      </div>
      <p class="mt-1 text-xs">Total registradas</p>
    </a>

    <!-- Tarjeta Ofertas -->
    <a href="{{ url('/offers') }}" class="group relative rounded-xl border-dashed border-2 border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 hover:bg-indigo-100 dark:hover:bg-slate-700 shadow-md transition duration-300">
      <div class="flex items-center justify-between mb-3">
        <span class="text-sm text-gray-600 dark:text-slate-300">Ofertas</span>
        <span class="inline-flex items-center px-2 py-0.5 text-[11px] bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-300">Próx.</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="text-4xl font-semibold">{{ number_format($stats['offers'] ?? 0) }}</div>
        <i class="fa-solid fa-briefcase text-gray-400 opacity-75 group-hover:opacity-100 transition"></i>
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">En preparación</p>
    </a>

    <!-- Tarjeta Documentos -->
    <a href="{{ url('/documents') }}" class="group relative rounded-xl border-dashed border-2 border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 hover:bg-indigo-100 dark:hover:bg-slate-700 shadow-md transition duration-300">
      <div class="flex items-center justify-between mb-3">
        <span class="text-sm text-gray-600 dark:text-slate-300">Documentos</span>
        <span class="inline-flex items-center px-2 py-0.5 text-[11px] bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-300">Próx.</span>
      </div>
      <div class="flex items-center gap-2">
        <div class="text-4xl font-semibold">{{ number_format($stats['documents'] ?? 0) }}</div>
        <i class="fa-solid fa-folder-open text-gray-400 opacity-75 group-hover:opacity-100 transition"></i>
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">En preparación</p>
    </a>
  </div>

  {{-- Gráficas --}}
  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Gráfico 1 -->
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Distribución general</h2>
        <span class="text-xs text-gray-400">Participantes vs Empresas</span>
      </div>
      <div class="relative">
        <canvas id="chartDoughnut" height="220"></canvas>
      </div>
    </div>

    <!-- Gráfico 2 -->
    <div class="xl:col-span-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-lg">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Actividad reciente</h2>
        <span class="text-xs text-gray-400">Últimos 6 meses (ejemplo)</span>
      </div>
      <div class="relative">
        <canvas id="chartBars" height="220"></canvas>
      </div>
    </div>
  </div>

  {{-- Listados rápidos --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Últimos Participantes -->
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-lg">
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-slate-700">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200">
          <i class="fa-regular fa-id-badge mr-2 text-indigo-600 dark:text-indigo-300"></i>
          Últimos participantes
        </h2>
        <a href="{{ url('/participants') }}" class="text-xs text-indigo-600 dark:text-indigo-300 hover:underline">Ver todos</a>
      </div>
      <ul class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($lastParticipants ?? [] as $p)
          <li class="px-4 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
            <div>
              <div class="font-medium text-gray-800 dark:text-slate-100">{{ $p->nombre }}</div>
              <div class="text-xs text-gray-500 dark:text-slate-400">{{ $p->dni_nie }} · {{ $p->email ?: '—' }}</div>
            </div>
            <a href="{{ route('viewparticipant', $p) }}" class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/60">Ver</a>
          </li>
        @empty
          <li class="px-4 py-6 text-center text-gray-500 dark:text-slate-400">Sin registros</li>
        @endforelse
      </ul>
    </div>

    <!-- Últimas Empresas -->
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-lg">
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-slate-700">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200">
          <i class="fa-regular fa-building mr-2 text-indigo-600 dark:text-indigo-300"></i>
          Últimas empresas
        </h2>
        <a href="{{ url('/companies') }}" class="text-xs text-indigo-600 dark:text-indigo-300 hover:underline">Ver todas</a>
      </div>
      <ul class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($lastCompanies ?? [] as $c)
          <li class="px-4 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
            <div>
              <div class="font-medium text-gray-800 dark:text-slate-100">{{ $c->nombre }}</div>
              <div class="text-xs text-gray-500 dark:text-slate-400">{{ $c->cif_nif }} · {{ $c->actividad ?: '—' }}</div>
            </div>
            <a href="{{ route('viewcompany', $c) }}" class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/60">Ver</a>
          </li>
        @empty
          <li class="px-4 py-6 text-center text-gray-500 dark:text-slate-400">Sin registros</li>
        @endforelse
      </ul>
    </div>
  </div>

@endsection
