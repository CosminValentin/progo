<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
        dark:false, sidebarOpen:false, nav:{gestion:true, docs:true},
        init(){
          const savedDark = localStorage.getItem('darkMode');
          const savedSide = localStorage.getItem('sidebarOpen');
          const savedNav  = localStorage.getItem('navGroups');

          this.dark = savedDark ? JSON.parse(savedDark) : window.matchMedia('(prefers-color-scheme: dark)').matches;
          this.sidebarOpen = savedSide ? JSON.parse(savedSide) : false;
          if(savedNav){ try{ this.nav = JSON.parse(savedNav) }catch(e){} }

          document.documentElement.classList.toggle('dark', this.dark);
        },
        toggleDark(){
          this.dark=!this.dark;
          document.documentElement.classList.toggle('dark', this.dark);
          localStorage.setItem('darkMode', JSON.stringify(this.dark));
        },
        toggleSidebar(){
          this.sidebarOpen = !this.sidebarOpen;
          localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
        },
        toggleGroup(key){
          this.nav[key]=!this.nav[key];
          localStorage.setItem('navGroups', JSON.stringify(this.nav));
        }
      }" x-init="init()" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{ asset('brand/progo-logo.png') }}">
  <title>@yield('title', config('app.name', 'PROGO'))</title>

  <script>tailwind = { config: { darkMode: 'class' } }</script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>

  <style>
    [x-cloak]{display:none!important}
    :root{color-scheme:light dark}
    .thin-scrollbar{scrollbar-width:thin;scrollbar-color:rgba(148,163,184,.6) transparent}
    .thin-scrollbar::-webkit-scrollbar{width:8px;height:8px}
    .thin-scrollbar::-webkit-scrollbar-thumb{background:rgba(148,163,184,.6);border-radius:8px}
  </style>
</head>

<body class="bg-gray-50 text-gray-800 dark:bg-slate-900 dark:text-slate-100 antialiased h-full">

  <!-- Overlay móvil -->
  <div x-cloak x-show="sidebarOpen" x-transition.opacity
       class="fixed inset-0 z-30 bg-black/40 md:hidden"
       @click="toggleSidebar()"></div>

  <!-- ========== SIDEBAR ========== -->
@php
  // ===== Navegación (tus rutas) =====
  $nav = [
    'gestion' => [
      'label' => 'Gestión',
      'items' => [
        ['url'=>'/participants',        'label'=>'Personas participantes',        'icon'=>'fa-users',           'match'=>'participants*'],
        ['url'=>'/companies',           'label'=>'Empresas',             'icon'=>'fa-building',        'match'=>'companies*'],
        ['url'=>'/agreements',          'label'=>'Convenios con empresas',            'icon'=>'fa-handshake',       'match'=>'agreements*'],
        ['url'=>'/offers',              'label'=>'Ofertas de empleo',              'icon'=>'fa-briefcase',       'match'=>'offers*'],
        ['url'=>'/applications',        'label'=>'Solicitudes o Candidaturas',         'icon'=>'fa-user-check',      'match'=>'applications*'],
        ['url'=>'/contracts',           'label'=>'Contratos laborales',            'icon'=>'fa-file-signature',  'match'=>'contracts*'],
        ['url'=>'/ss-records',          'label'=>'Altas Seguridad Social',         'icon'=>'fa-shield-halved',   'match'=>'ss-records*'],
        ['url'=>'/insertion_checks',    'label'=>'Seguimiento laboral','icon'=>'fa-circle-check',   'match'=>'insertion_checks*'],
      ],
    ],
    'docs' => [
      'label' => 'Documentos',
      'items' => [
        ['url'=>'/documents',           'label'=>'Documentos',           'icon'=>'fa-folder-open',     'match'=>'documents*'],
        ['url'=>'/cvs',                 'label'=>'CVs',                  'icon'=>'fa-file-lines',      'match'=>'cvs*'],
        ['url'=>'/notas',               'label'=>'Notas de Trabajador',  'icon'=>'fa-note-sticky',     'match'=>'notas*'],
      ],
    ],
  ];

  $isActive = fn(string $pattern) => request()->is($pattern);
@endphp

<aside class="fixed inset-y-0 left-0 z-40 w-64 md:w-64 transform
              bg-[#27384a] text-slate-100 border-r border-black/10
              thin-scrollbar md:translate-x-0 transition-transform duration-300 ease-in-out shadow-xl"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

  {{-- Brand --}}
  <div class="h-16 flex items-center gap-3 px-5 border-b border-white/10">
    <x-application-logo size="h-9" />
    <div class="text-xl font-extrabold tracking-wide">PROGO</div>
  </div>

  {{-- Menú --}}
  <nav class="px-3 py-4 space-y-3">

    {{-- Dashboard (opcional) --}}
    <a href="{{ url('/home') }}"
       class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[15px] font-medium
              hover:bg-white/10 transition
              {{ request()->is('home') ? 'bg-sky-600 text-white shadow-inner' : 'text-slate-200' }}">
      <i class="fa-solid fa-gauge w-5 text-center"></i>
      <span>Dashboard</span>
    </a>

    {{-- GRUPO: Gestión --}}
    <div>
      <button type="button"
              @click="toggleGroup('gestion')"
              class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-left
                     text-[15px] font-semibold tracking-normal"
              :class="nav.gestion ? 'bg-sky-600 text-white shadow-inner' : 'text-slate-100 hover:bg-white/10 transition'">
        <span class="inline-flex items-center gap-3">
          <i class="fa-solid fa-briefcase w-5 text-center"></i>
          <span class="leading-tight">{{ $nav['gestion']['label'] }}</span>
        </span>
        <i class="fa-solid fa-chevron-up text-xs transition" :class="nav.gestion ? 'rotate-0' : 'rotate-180'"></i>
      </button>

      <div x-cloak x-show="nav.gestion" x-collapse>
        <ul class="mt-2 space-y-2">
          @foreach($nav['gestion']['items'] as $link)
            @php $active = $isActive($link['match']); @endphp
            <li>
              <a href="{{ url($link['url']) }}"
                 class="flex items-center gap-3 px-4 py-2.5 rounded-lg ml-2 transition
                        {{ $active ? 'bg-sky-600 text-white shadow-inner'
                                   : 'text-slate-200 hover:bg-white/10' }}">
                <i class="fa-solid {{ $link['icon'] }} w-5 text-center opacity-90"></i>
                <span class="text-[15px]">{{ $link['label'] }}</span>
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>

    {{-- GRUPO: Documentos --}}
    <div>
      <button type="button"
              @click="toggleGroup('docs')"
              class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg text-left
                     text-[15px] font-semibold tracking-normal"
              :class="nav.docs ? 'bg-sky-600 text-white shadow-inner' : 'text-slate-100 hover:bg-white/10 transition'">
        <span class="inline-flex items-center gap-3">
          <i class="fa-solid fa-folder-open w-5 text-center"></i>
          <span class="leading-tight">{{ $nav['docs']['label'] }}</span>
        </span>
        <i class="fa-solid fa-chevron-up text-xs transition" :class="nav.docs ? 'rotate-0' : 'rotate-180'"></i>
      </button>

      <div x-cloak x-show="nav.docs" x-collapse>
        <ul class="mt-2 space-y-2">
          @foreach($nav['docs']['items'] as $link)
            @php $active = $isActive($link['match']); @endphp
            <li>
              <a href="{{ url($link['url']) }}"
                 class="flex items-center gap-3 px-4 py-2.5 rounded-lg ml-2 transition
                        {{ $active ? 'bg-sky-600 text-white shadow-inner'
                                   : 'text-slate-200 hover:bg-white/10' }}">
                <i class="fa-solid {{ $link['icon'] }} w-5 text-center opacity-90"></i>
                <span class="text-[15px]">{{ $link['label'] }}</span>
              </a>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </nav>

  {{-- Footer --}}
  <div class="mt-auto p-4 text-[11px] text-slate-300/80 border-t border-white/10">
    <div class="flex items-center justify-between">
      <span>© {{ date('Y') }} PROGO</span>
      <span class="opacity-70">v{{ app()->version() }}</span>
    </div>
  </div>
</aside>


  <!-- ========== MAIN ========== -->
  <div class="md:pl-64 min-h-screen flex flex-col">

    <header class="h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8
                   bg-white/90 dark:bg-slate-800/90 backdrop-blur
                   border-b border-gray-200 dark:border-slate-700 sticky top-0 z-20 shadow-lg">
      <div class="flex items-center gap-2">
        <button class="md:hidden p-2 rounded-md bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600"
                @click="toggleSidebar()" title="Menú">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400">
          @yield('breadcrumb')
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button @click="toggleDark()"
                class="p-2 rounded-md border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors duration-200"
                :title="dark ? 'Modo claro' : 'Modo oscuro'">
          <i :class="dark ? 'fa-solid fa-sun' : 'fa-solid fa-moon'"></i>
        </button>

        @auth
          <div class="relative" x-data="{open:false}" @keydown.escape="open=false">
            <button @click="open=!open"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-gray-200 dark:bg-slate-700 text-sm font-medium hover:bg-gray-300 dark:hover:bg-slate-600">
              <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
              <i class="fa-solid fa-chevron-down text-xs opacity-70"></i>
            </button>
            <div x-cloak x-show="open" x-transition @click.outside="open=false"
                 class="absolute right-0 mt-2 w-56 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-lg z-50">
              <div class="py-2">
                <a href="{{ url('/home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-slate-200 dark:hover:bg-slate-700/60">
                  <i class="fa-solid fa-house me-2"></i> Inicio
                </a>
                <div class="my-2 border-t border-gray-200 dark:border-slate-700"></div>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="w-full text-left px-4 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Salir
                  </button>
                </form>
              </div>
            </div>
          </div>
        @endauth
      </div>
    </header>

    <main class="flex-1 p-6 sm:p-8 lg:p-10">
      @hasSection('header')
        <div class="mb-6">@yield('header')</div>
      @endif
      @yield('content')
    </main>
  </div>
  @include('components.modal-remote')
  @yield('scripts')
</body>
</html>
