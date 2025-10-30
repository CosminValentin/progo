<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - PROGO</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    .bg-grad { background: linear-gradient(135deg, #4f46e5, #7c3aed, #06b6d4); }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-slate-900">

  <div class="max-w-md w-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
    <div class="text-center mb-6">
      <img src="{{ asset('brand/progo-logo.png') }}" alt="PROGO" class="w-20 mx-auto mb-2">
      <h2 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Crear usuario</h2>
      <p class="text-slate-500 dark:text-slate-400 text-sm">Solo nombre y contraseña</p>
    </div>

    @if($errors->any())
      <div class="mb-4 p-3 rounded-xl bg-rose-100 text-rose-700 text-sm font-semibold">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
      @csrf

      <div>
        <label class="block text-sm font-medium mb-1">Nombre de usuario</label>
        <input type="text" name="name" value="{{ old('name') }}" required
               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Contraseña</label>
        <input type="password" name="password" required
               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
      </div>

      <button type="submit"
              class="w-full py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition">
        Crear usuario
      </button>

      <p class="text-sm text-center text-slate-500 dark:text-slate-400 mt-3">
        ¿Ya tienes cuenta?
        <a href="{{ url('/login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Inicia sesión</a>
      </p>
    </form>
  </div>
</body>
</html>
