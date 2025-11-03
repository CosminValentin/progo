@extends('layouts.app_windmill')

@section('header')
  {{-- HERO superior compacto --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-fuchsia-600 p-5 sm:p-6 shadow-xl">
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
      <div>
        <p class="text-indigo-100/90 text-xs mb-0.5">
          {{ now()->translatedFormat('l, d \d\e F Y') }}
        </p>
        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
          Hola, {{ Auth::user()->name }} 👋
        </h1>
        <p class="mt-1 text-indigo-50/95 text-sm">
          Vista rápida de actividad y accesos clave.
        </p>
      </div>

      {{-- Accesos rápidos compactos --}}
      <div class="flex flex-wrap gap-2">
        <a href="{{ route('addparticipant') }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-white/95 text-indigo-700 hover:bg-white shadow-sm">
          <i class="fa-solid fa-user-plus"></i> Participante
        </a>
        <a href="{{ route('addcompany') }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-white/10 text-white hover:bg-white/20 ring-1 ring-white/30">
          <i class="fa-solid fa-building-circle-plus"></i> Empresa
        </a>
        <a href="{{ route('offers.create') }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-white/10 text-white hover:bg-white/20 ring-1 ring-white/30">
          <i class="fa-solid fa-briefcase"></i> Oferta
        </a>
      </div>
    </div>

    {{-- adornos blur discretos --}}
    <div class="pointer-events-none absolute -top-10 -right-10 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
  </div>
@endsection

@section('content')
  {{-- KPIs principales (más densos) --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mt-5">
    {{-- Participantes --}}
    <a href="{{ url('/participants') }}"
       class="group rounded-2xl bg-white dark:bg-slate-800 shadow-md ring-1 ring-black/5 dark:ring-white/10 p-4 transition hover:shadow-lg">
      <div class="flex items-start justify-between">
        <div>
          <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-slate-400">Participantes</p>
          <h3 class="mt-0.5 text-2xl font-bold text-gray-900 dark:text-white">
            {{ number_format($stats['participants'] ?? 0) }}
          </h3>
          <p class="text-[11px] text-gray-400 mt-0.5">Total registrados</p>
        </div>
        <div class="shrink-0 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 p-2.5">
          <i class="fa-solid fa-users text-indigo-600 dark:text-indigo-300"></i>
        </div>
      </div>
      <div class="mt-3 h-10">
        <canvas id="sparkParticipants" height="40"></canvas>
      </div>
    </a>

    {{-- Empresas --}}
    <a href="{{ url('/companies') }}"
       class="group rounded-2xl bg-white dark:bg-slate-800 shadow-md ring-1 ring-black/5 dark:ring-white/10 p-4 transition hover:shadow-lg">
      <div class="flex items-start justify-between">
        <div>
          <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-slate-400">Empresas</p>
          <h3 class="mt-0.5 text-2xl font-bold text-gray-900 dark:text-white">
            {{ number_format($stats['companies'] ?? 0) }}
          </h3>
          <p class="text-[11px] text-gray-400 mt-0.5">Total registradas</p>
        </div>
        <div class="shrink-0 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 p-2.5">
          <i class="fa-solid fa-building text-emerald-600 dark:text-emerald-300"></i>
        </div>
      </div>
      <div class="mt-3 h-10">
        <canvas id="sparkCompanies" height="40"></canvas>
      </div>
    </a>

    {{-- Ofertas --}}
    <a href="{{ route('offers') }}"
       class="group rounded-2xl bg-white dark:bg-slate-800 shadow-md ring-1 ring-black/5 dark:ring-white/10 p-4 transition hover:shadow-lg">
      <div class="flex items-start justify-between">
        <div>
          <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-slate-400">Ofertas</p>
          <h3 class="mt-0.5 text-2xl font-bold text-gray-900 dark:text-white">
            {{ number_format($stats['offers'] ?? 0) }}
          </h3>
          <p class="text-[11px] text-gray-400 mt-0.5">Publicadas</p>
        </div>
        <div class="shrink-0 rounded-xl bg-amber-50 dark:bg-amber-900/30 p-2.5">
          <i class="fa-solid fa-briefcase text-amber-600 dark:text-amber-300"></i>
        </div>
      </div>
      <div class="mt-3 h-10">
        <canvas id="sparkOffers" height="40"></canvas>
      </div>
    </a>

    {{-- Documentos --}}
    <a href="{{ url('/documents') }}"
       class="group rounded-2xl bg-white dark:bg-slate-800 shadow-md ring-1 ring-black/5 dark:ring-white/10 p-4 transition hover:shadow-lg">
      <div class="flex items-start justify-between">
        <div>
          <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-slate-400">Documentos</p>
          <h3 class="mt-0.5 text-2xl font-bold text-gray-900 dark:text-white">
            {{ number_format($stats['documents'] ?? 0) }}
          </h3>
          <p class="text-[11px] text-gray-400 mt-0.5">Totales</p>
        </div>
        <div class="shrink-0 rounded-xl bg-sky-50 dark:bg-sky-900/30 p-2.5">
          <i class="fa-solid fa-folder-open text-sky-600 dark:text-sky-300"></i>
        </div>
      </div>
      <div class="mt-3 h-10">
        <canvas id="sparkDocuments" height="40"></canvas>
      </div>
    </a>
  </div>

  {{-- Resumen rápido + Gráficas en una fila compacta --}}
  <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mt-5">
    {{-- Resumen rápido (chips) --}}
    <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-md">
      <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200 mb-3">Resumen rápido</h2>
      <div class="flex flex-wrap gap-2">
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-200 text-xs">
          <i class="fa-regular fa-id-badge"></i> {{ number_format($stats['participants'] ?? 0) }} participantes
        </span>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-200 text-xs">
          <i class="fa-regular fa-building"></i> {{ number_format($stats['companies'] ?? 0) }} empresas
        </span>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-200 text-xs">
          <i class="fa-solid fa-briefcase"></i> {{ number_format($stats['offers'] ?? 0) }} ofertas
        </span>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-200 text-xs">
          <i class="fa-solid fa-folder-open"></i> {{ number_format($stats['documents'] ?? 0) }} documentos
        </span>
      </div>

      {{-- atajos secundarios --}}
      <div class="mt-4 grid grid-cols-2 gap-2">
        <a href="{{ route('agreements.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 text-xs">
          <i class="fa-solid fa-handshake text-indigo-500"></i> Convenios
        </a>
        <a href="{{ route('participants') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 text-xs">
          <i class="fa-solid fa-users text-emerald-500"></i> Participantes
        </a>
        <a href="{{ route('offers') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 text-xs">
          <i class="fa-solid fa-briefcase text-amber-500"></i> Ofertas
        </a>
        <a href="{{ route('documents.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 text-xs">
          <i class="fa-solid fa-folder-open text-sky-500"></i> Documentos
        </a>
      </div>
    </div>

    {{-- Doughnut compacto --}}
    <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-md">
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Distribución</h2>
        <span class="text-[11px] text-gray-400">Participantes / Empresas</span>
      </div>
      <div class="relative h-36">
        <canvas id="chartDoughnut" height="144"></canvas>
        <div id="chartDoughnutEmpty" class="hidden absolute inset-0 grid place-items-center text-xs text-gray-500 dark:text-slate-400">
          Sin datos suficientes
        </div>
      </div>
    </div>

    {{-- Barras compactas --}}
    <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-md">
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200">Actividad (6 meses)</h2>
        <span class="text-[11px] text-gray-400">Demo</span>
      </div>
      <div class="relative h-36">
        <canvas id="chartBars" height="144"></canvas>
        <div id="chartBarsEmpty" class="hidden absolute inset-0 grid place-items-center text-xs text-gray-500 dark:text-slate-400">
          Sin datos suficientes
        </div>
      </div>
    </div>
  </div>

  {{-- Listados recientes en dos columnas (más densos) --}}
  <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-5">
    {{-- Últimos Participantes --}}
    <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-md">
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-slate-700">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200">
          <i class="fa-regular fa-id-badge mr-2 text-indigo-600 dark:text-indigo-300"></i>
          Últimos participantes
        </h2>
        <a href="{{ url('/participants') }}" class="text-[11px] text-indigo-600 dark:text-indigo-300 hover:underline">Ver todos</a>
      </div>
      <ul class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($lastParticipants ?? [] as $p)
          <li class="px-4 py-2.5 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
            <div class="min-w-0">
              <div class="text-sm font-medium text-gray-800 dark:text-slate-100 truncate">{{ $p->nombre }}</div>
              <div class="text-[11px] text-gray-500 dark:text-slate-400 truncate">
                {{ $p->dni_nie }} · {{ $p->email ?: '—' }}
              </div>
            </div>
            <a href="{{ route('viewparticipant', $p) }}"
               class="text-[11px] px-2.5 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/60">
              Ver
            </a>
          </li>
        @empty
          <li class="px-4 py-6 text-center text-gray-500 dark:text-slate-400 text-sm">Sin registros</li>
        @endforelse
      </ul>
    </div>

    {{-- Últimas Empresas --}}
    <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-md">
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-slate-700">
        <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200">
          <i class="fa-regular fa-building mr-2 text-indigo-600 dark:text-indigo-300"></i>
          Últimas empresas
        </h2>
        <a href="{{ url('/companies') }}" class="text-[11px] text-indigo-600 dark:text-indigo-300 hover:underline">Ver todas</a>
      </div>
      <ul class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($lastCompanies ?? [] as $c)
          <li class="px-4 py-2.5 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/40 transition">
            <div class="min-w-0">
              <div class="text-sm font-medium text-gray-800 dark:text-slate-100 truncate">{{ $c->nombre }}</div>
              <div class="text-[11px] text-gray-500 dark:text-slate-400 truncate">{{ $c->cif_nif }} · {{ $c->actividad ?: '—' }}</div>
            </div>
            <a href="{{ route('viewcompany', $c) }}"
               class="text-[11px] px-2.5 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/60">
              Ver
            </a>
          </li>
        @empty
          <li class="px-4 py-6 text-center text-gray-500 dark:text-slate-400 text-sm">Sin registros</li>
        @endforelse
      </ul>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    // Usa Chart.js si está disponible
    document.addEventListener('DOMContentLoaded', () => {
      const hasChart = !!window.Chart;

      // Helpers
      const makeSpark = (id, data) => {
        const el = document.getElementById(id);
        if (!el || !hasChart || !data?.length) return;
        new Chart(el.getContext('2d'), {
          type: 'line',
          data: {
            labels: data.map((_, i) => i + 1),
            datasets: [{ data, fill: false, tension: 0.35, pointRadius: 0, borderWidth: 2 }]
          },
          options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            elements: { line: { borderColor: getComputedStyle(el).color } },
            scales: { x: { display: false }, y: { display: false } }
          }
        });
      };

      // Datos demo para sparklines (compáctos)
      makeSpark('sparkParticipants', [3,5,4,6,8,7,9,8,10,12,11,13]);
      makeSpark('sparkCompanies',    [2,3,2,4,5,6,7,6,7,8,8,9]);
      makeSpark('sparkOffers',       [1,1,2,3,2,4,3,5,6,5,7,8]);
      makeSpark('sparkDocuments',    [4,4,5,6,7,8,7,9,9,10,11,13]);

      // Donut: participantes vs empresas (compacto)
      const d1 = document.getElementById('chartDoughnut');
      const d1Empty = document.getElementById('chartDoughnutEmpty');
      if (d1) {
        const participants = Number(@json($stats['participants'] ?? 0));
        const companies    = Number(@json($stats['companies'] ?? 0));
        const hasData = (participants + companies) > 0 && hasChart;

        if (hasData) {
          new Chart(d1.getContext('2d'), {
            type: 'doughnut',
            data: {
              labels: ['Participantes','Empresas'],
              datasets: [{ data: [participants, companies] }]
            },
            options: {
              plugins: { legend: { display: false } },
              cutout: '68%'
            }
          });
        } else {
          d1.style.display = 'none';
          if (d1Empty) d1Empty.classList.remove('hidden');
        }
      }

      // Barras: últimos 6 meses (demo compacta)
      const d2 = document.getElementById('chartBars');
      const d2Empty = document.getElementById('chartBarsEmpty');
      if (d2) {
        const canDraw = hasChart;
        if (canDraw) {
          const labels = @json(collect(range(5,0))->map(fn($i) => now()->subMonths($i)->translatedFormat('M'))->values());
          new Chart(d2.getContext('2d'), {
            type: 'bar',
            data: {
              labels,
              datasets: [
                { label: 'Participantes', data: [8, 12, 10, 14, 18, 16] },
                { label: 'Empresas',      data: [4, 6, 5, 7, 9, 8] }
              ]
            },
            options: {
              responsive: true, maintainAspectRatio: false,
              plugins: { legend: { display: false } },
              scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { stepSize: 5 } } }
            }
          });
        } else {
          d2.style.display = 'none';
          if (d2Empty) d2Empty.classList.remove('hidden');
        }
      }
    });
  </script>
@endsection
