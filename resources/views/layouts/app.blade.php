<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'PROGO') }}</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
  <div class="min-h-screen bg-gray-100">
    <nav class="w-full bg-gray-800 text-white">
      <div class="max-w-7xl mx-auto px-4 h-12 flex items-center justify-between">
        <span class="font-semibold">PROGO</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="text-sm bg-white/10 hover:bg-white/20 px-3 py-1 rounded">Salir</button>
        </form>
      </div>
    </nav>

    <main class="py-6">
      @yield('content')
    </main>
  </div>
</body>
</html>
