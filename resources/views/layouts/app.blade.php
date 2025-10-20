<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'PROGO') }}</title>

  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Tailwind & Alpine (sin npm) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-800">
  <div class="min-h-screen" x-data="{ open:false }">
<nav class="bg-white border-b border-gray-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex h-14 items-center justify-between">
      <div class="flex items-center gap-8">
        <a href="{{ url('/home') }}" class="text-lg font-semibold tracking-wide">PROGO</a>

        <div class="hidden md:flex items-center gap-1">
          <a href="{{ url('/participants') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('participants*') ? 'font-semibold' : '' }}">Participantes</a>
          <a href="{{ url('/companies') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('companies*') ? 'font-semibold' : '' }}">Empresas</a>
          <a href="{{ url('/agreements') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('agreements*') ? 'font-semibold' : '' }}">Convenios</a>
          <a href="{{ url('/offers') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('offers*') ? 'font-semibold' : '' }}">Ofertas</a>
          <a href="{{ url('/applications') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('applications*') ? 'font-semibold' : '' }}">Candidaturas</a>
          <a href="{{ url('/contracts') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('contracts*') ? 'font-semibold' : '' }}">Contratos</a>
          <a href="{{ url('/ss_records') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('ss_records*') ? 'font-semibold' : '' }}">Registros SS</a>
          <a href="{{ url('/insertion_checks') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('insertion_checks*') ? 'font-semibold' : '' }}">Validación Inserciones</a>
          <a href="{{ url('/documents') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('documents*') ? 'font-semibold' : '' }}">Documentos</a>

          @if(auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('admin'))
            <a href="{{ url('/admin') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-50 {{ request()->is('admin*') ? 'font-semibold' : '' }}">Admin</a>
          @endif
        </div>
      </div>

      <div class="flex items-center gap-3">
        @auth
          <span class="hidden sm:inline text-sm text-gray-600">Hola, {{ Auth::user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-sm bg-gray-800 text-white px-3 py-1.5 rounded-md hover:bg-black">Salir</button>
          </form>
        @endauth
      </div>
    </div>
  </div>
</nav>
    <!-- /NAVBAR -->

    <!-- CONTENIDO -->
    <main class="py-6">
      @yield('content')
    </main>
  </div>
</body>
</html>
