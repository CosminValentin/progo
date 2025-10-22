<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
        dark:false, sidebarOpen:false,
        init(){
          const saved = localStorage.getItem('darkMode');
          this.dark = saved ? JSON.parse(saved) : window.matchMedia('(prefers-color-scheme: dark)').matches;
          document.documentElement.classList.toggle('dark', this.dark);
        },
        toggleDark(){
          this.dark = !this.dark;
          document.documentElement.classList.toggle('dark', this.dark);
          localStorage.setItem('darkMode', JSON.stringify(this.dark));
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" referrerpolicy="no-referrer"/>

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
       @click="sidebarOpen=false"></div>

  <!-- SIDEBAR -->
  <aside class="fixed inset-y-0 left-0 z-40 w-64 transform bg-white dark:bg-slate-800
                 border-r border-gray-200 dark:border-slate-700 thin-scrollbar
                 md:translate-x-0 transition-transform duration-300 ease-in-out shadow-lg"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
    <div class="h-16 flex items-center px-6 border-b border-gray-200/70 dark:border-slate-700/60">
      <a href="{{ url('/home') }}" class="inline-flex items-center gap-2">
        <x-application-logo size="h-16 md:h-24" />
        <span class="sr-only">PROGO</span>
      </a>
    </div>

    @php
      $links = [
        ['url'=>'/participants','label'=>'Participantes','icon'=>'fa-users'],
        ['url'=>'/companies','label'=>'Empresas','icon'=>'fa-building'],
        ['url'=>'/agreements','label'=>'Convenios','icon'=>'fa-handshake'],
        ['url'=>'/offers','label'=>'Ofertas','icon'=>'fa-briefcase'],
        ['url'=>'/applications','label'=>'Candidaturas','icon'=>'fa-user-check'],
        ['url'=>'/contracts','label'=>'Contratos','icon'=>'fa-file-signature'],
        ['url'=>'/ss_records','label'=>'Registros SS','icon'=>'fa-shield-halved'],
        ['url'=>'/insertion_checks','label'=>'Validación Inserciones','icon'=>'fa-circle-check'],
        ['url'=>'/documents','label'=>'Documentos','icon'=>'fa-folder-open'],
      ];
    @endphp

    <nav class="px-3 py-4 space-y-2">
      @foreach ($links as $link)
        <a href="{{ url($link['url']) }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-base font-semibold transition-colors duration-200 
                  hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-slate-700/60 dark:hover:text-indigo-200
                  {{ request()->is(ltrim($link['url'], '/').'*')
                    ? 'bg-indigo-50 text-indigo-700 dark:bg-slate-700/70 dark:text-indigo-200'
                    : 'text-gray-700 dark:text-slate-200' }}">
          <i class="fa-solid {{ $link['icon'] }} w-5 text-center"></i>
          <span class="font-medium">{{ $link['label'] }}</span>
        </a>
      @endforeach

      @if(auth()->check() && method_exists(auth()->user(),'hasRole') && auth()->user()->hasRole('admin'))
        <div class="pt-3 mt-3 border-t border-gray-200 dark:border-slate-700"></div>
        <a href="{{ url('/admin') }}"
           class="flex items-center gap-3 px-4 py-2 rounded-lg text-base font-semibold transition-colors duration-200 
                  hover:bg-indigo-50 hover:text-indigo-700 dark:hover:bg-slate-700/60 dark:hover:text-indigo-200
                  {{ request()->is('admin*')
                    ? 'bg-indigo-50 text-indigo-700 dark:bg-slate-700/70 dark:text-indigo-200'
                    : 'text-gray-700 dark:text-slate-200' }}">
          <i class="fa-solid fa-gear w-5 text-center"></i>
          <span class="font-medium">Admin</span>
        </a>
      @endif
    </nav>
  </aside>

  <div class="md:pl-64 min-h-screen flex flex-col">

    <header class="h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8
                   bg-white/90 dark:bg-slate-800/90 backdrop-blur
                   border-b border-gray-200 dark:border-slate-700 sticky top-0 z-20 shadow-lg">
      <div class="flex items-center gap-2">
        <button class="md:hidden p-2 rounded-md bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600"
                @click="sidebarOpen = !sidebarOpen">
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
</body>
</html>
