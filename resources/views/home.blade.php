@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-2xl font-bold">Inicio</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">
        Bienvenido/a, {{ Auth::user()->name }}. Resumen de actividad y accesos directos.
      </p>
    </div>
  </div>
@endsection

@section('content')
  {{-- KPIs / Accesos rápidos --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="{{ url('/participants') }}"
       class="group rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow hover:shadow-md transition">
      <div class="flex items-center justify-between">
        <span class="text-sm text-gray-500 dark:text-slate-400">Participantes</span>
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] bg-indigo-100 text-indigo-700
                      dark:bg-indigo-900/30 dark:text-indigo-200">CRUD</span>
      </div>
      <div class="mt-2 flex items-baseline gap-2">
        <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100">
          {{ number_format($stats['participants'] ?? 0) }}
        </div>
        <i class="fa-solid fa-users text-indigo-600 dark:text-indigo-300 opacity-80 group-hover:opacity-100"></i>
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Total registrados</p>
    </a>

    <a href="{{ url('/companies') }}"
       class="group rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow hover:shadow-md transition">
      <div class="flex items-center justify-between">
        <span class="text-sm text-gray-500 dark:text-slate-400">Empresas</span>
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] bg-indigo-100 text-indigo-700
                      dark:bg-indigo-900/30 dark:text-indigo-200">CRUD</span>
      </div>
      <div class="mt-2 flex items-baseline gap-2">
        <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100">
          {{ number_format($stats['companies'] ?? 0) }}
        </div>
        <i class="fa-solid fa-building text-indigo-600 dark:text-indigo-300 opacity-80 group-hover:opacity-100"></i>
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Total registradas</p>
    </a>

    {{-- Tarjetas preparadas para futuras secciones --}}
    <a href="{{ url('/offers') }}"
       class="group rounded-xl border border-dashed border-gray-200 dark:border-slate-700 bg-white/80 dark:bg-slate-800/80 p-4 hover:bg-white dark:hover:bg-slate-800 shadow transition">
      <div class="flex items-center justify-between">
        <span class="text-sm text-gray-500 dark:text-slate-400">Ofertas</span>
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] bg-gray-100 text-gray-600
                      dark:bg-slate-700 dark:text-slate-300">Próx.</span>
      </div>
      <div class="mt-2 flex items-baseline gap-2">
        <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100">{{ number_format($stats['offers'] ?? 0) }}</div>
        <i class="fa-solid fa-briefcase text-gray-400"></i>
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">En preparación</p>
    </a>

    <a href="{{ url('/documents') }}"
       class="group rounded-xl border border-dashed border-gray-200 dark:border-slate-700 bg-white/80 dark:bg-slate-800/80 p-4 hover:bg-white dark:hover:bg-slate-800 shadow transition">
      <div class="flex items-center justify-between">
        <span class="text-sm text-gray-500 dark:text-slate-400">Documentos</span>
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] bg-gray-100 text-gray-600
                      dark:bg-slate-700 dark:text-slate-300">Próx.</span>
      </div>
      <div class="mt-2 flex items-baseline gap-2">
        <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100">{{ number_format($stats['documents'] ?? 0) }}</div>
        <i class="fa-solid fa-folder-open text-gray-400"></i>
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">En preparación</p>
    </a>
  </div>

  {{-- Tarjetas con gráficas --}}
  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Gráfico 1: Distribución simple (doughnut) --}}
    <div class="xl:col-span-1 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Distribución general</h2>
        <span class="text-xs text-gray-400">Participantes vs Empresas</span>
      </div>
      <div class="relative">
        <canvas id="chartDoughnut" height="220"></canvas>
      </div>
    </div>

    {{-- Gráfico 2: Barras (ejemplo de actividad mensual dummy) --}}
    <div class="xl:col-span-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow">
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
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow">
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
              <div class="font-medium">{{ $p->nombre }}</div>
              <div class="text-xs text-gray-500 dark:text-slate-400">{{ $p->dni_nie }} · {{ $p->email ?: '—' }}</div>
            </div>
            <a href="{{ route('viewparticipant', $p) }}" class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/60">Ver</a>
          </li>
        @empty
          <li class="px-4 py-6 text-center text-gray-500 dark:text-slate-400">Sin registros</li>
        @endforelse
      </ul>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow">
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
              <div class="font-medium">{{ $c->nombre }}</div>
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

  {{-- Chart.js CDN y script (sin NPM) --}}
 {{-- KPIs / Accesos rápidos (en vivo) --}}
<div
  x-data="{
    stats: @js($stats),
    fmt(n){ return new Intl.NumberFormat('es-ES').format(n||0) },
    async refresh(){
      try {
        const r = await fetch('{{ route('metrics') }}', { headers:{'X-Requested-With':'XMLHttpRequest'} });
        if(!r.ok) return;
        this.stats = await r.json();
      } catch(e) { /* silencio elegante */ }
    },
    init(){
      // Primer refresco inmediato
      this.refresh();
      // Refrescos periódicos
      this._timer = setInterval(()=>this.refresh(), 5000);
      // Al volver a la pestaña
      document.addEventListener('visibilitychange', () => { if(!document.hidden) this.refresh() });
    },
    destroy(){ clearInterval(this._timer) }
  }"
  class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6"
>

  <a href="{{ url('/participants') }}"
     class="group rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow hover:shadow-md transition">
    <div class="flex items-center justify-between">
      <span class="text-sm text-gray-500 dark:text-slate-400">Participantes</span>
      <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] bg-indigo-100 text-indigo-700
                    dark:bg-indigo-900/30 dark:text-indigo-200">CRUD</span>
    </div>
    <div class="mt-2 flex items-baseline gap-2">
      <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100" x-text="fmt(stats.participants)"></div>
      <i class="fa-solid fa-users text-indigo-600 dark:text-indigo-300 opacity-80 group-hover:opacity-100"></i>
    </div>
    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Total registrados</p>
  </a>

  <a href="{{ url('/companies') }}"
     class="group rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow hover:shadow-md transition">
    <div class="flex items-center justify-between">
      <span class="text-sm text-gray-500 dark:text-slate-400">Empresas</span>
      <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] bg-indigo-100 text-indigo-700
                    dark:bg-indigo-900/30 dark:text-indigo-200">CRUD</span>
    </div>
    <div class="mt-2 flex items-baseline gap-2">
      <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100" x-text="fmt(stats.companies)"></div>
      <i class="fa-solid fa-building text-indigo-600 dark:text-indigo-300 opacity-80 group-hover:opacity-100"></i>
    </div>
    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Total registradas</p>
  </a>

  {{-- Preparadas para futuras secciones; solo cambia la clave en metrics() y aquí --}}
  <a href="{{ url('/offers') }}"
     class="group rounded-xl border border-dashed border-gray-200 dark:border-slate-700 bg-white/80 dark:bg-slate-800/80 p-4 hover:bg-white dark:hover:bg-slate-800 shadow transition">
    <div class="flex items-center justify-between">
      <span class="text-sm text-gray-500 dark:text-slate-400">Ofertas</span>
      <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] bg-gray-100 text-gray-600
                    dark:bg-slate-700 dark:text-slate-300">Próx.</span>
    </div>
    <div class="mt-2 flex items-baseline gap-2">
      <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100" x-text="fmt(stats.offers)"></div>
      <i class="fa-solid fa-briefcase text-gray-400"></i>
    </div>
    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">En preparación</p>
  </a>

  <a href="{{ url('/documents') }}"
     class="group rounded-xl border border-dashed border-gray-200 dark:border-slate-700 bg-white/80 dark:bg-slate-800/80 p-4 hover:bg-white dark:hover:bg-slate-800 shadow transition">
    <div class="flex items-center justify-between">
      <span class="text-sm text-gray-500 dark:text-slate-400">Documentos</span>
      <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] bg-gray-100 text-gray-600
                    dark:bg-slate-700 dark:text-slate-300">Próx.</span>
    </div>
    <div class="mt-2 flex items-baseline gap-2">
      <div class="text-2xl font-semibold text-gray-800 dark:text-slate-100" x-text="fmt(stats.documents)"></div>
      <i class="fa-solid fa-folder-open text-gray-400"></i>
    </div>
    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">En preparación</p>
  </a>
</div>

@endsection
