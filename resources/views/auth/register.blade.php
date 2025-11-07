<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro - PROGO</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-slate-900">

  <div class="max-w-2xl w-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-white/20">
    <div class="text-center mb-6">
      <img src="{{ asset('brand/progo-logo.png') }}" alt="PROGO" class="w-20 mx-auto mb-2">
      <h2 class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Crear usuario</h2>
      <p class="text-slate-500 dark:text-slate-400 text-sm">Rellena tus datos</p>
    </div>

    @if($errors->any())
      <div class="mb-6 p-3 rounded-xl bg-rose-100 text-rose-700 text-sm font-semibold">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
      @csrf

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Nombre de usuario <span class="text-rose-500">*</span></label>
          <input type="text" name="name" value="{{ old('name') }}" required
                 class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Contraseña <span class="text-rose-500">*</span></label>
          <input type="password" name="password" required
                 class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
          <p class="text-xs text-slate-500 mt-1">Mín. 6 caracteres, letras y números</p>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Email <span class="text-rose-500">*</span></label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none"
               placeholder="tu@email.com">
      </div>

      <div class="grid sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">DNI</label>
          <input type="text" name="dni" value="{{ old('dni') }}"
                 class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none" maxlength="20">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Nombre</label>
          <input type="text" name="first_name" value="{{ old('first_name') }}"
                 class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Apellido 1</label>
          <input type="text" name="last_name1" value="{{ old('last_name1') }}"
                 class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Apellido 2</label>
          <input type="text" name="last_name2" value="{{ old('last_name2') }}"
                 class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Fecha nacimiento</label>
          <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                 class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Sexo</label>
          <select name="gender"
                  class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
            <option value="" {{ old('gender')==='' ? 'selected' : '' }}>— Selecciona —</option>
            @foreach(['Varon','Mujer','Otro','Prefiero no decirlo'] as $opt)
              <option value="{{ $opt }}" {{ old('gender')===$opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Nivel formativo</label>
          <select name="education_level"
                  class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none">
            @php
              $levels = ['Sin Estudios','Primaria','ESO','Bachillerato','FP Básica','FP Media','FP Superior','Grado','Máster','Doctorado'];
            @endphp
            <option value="" {{ old('education_level')==='' ? 'selected' : '' }}>— Selecciona —</option>
            @foreach($levels as $l)
              <option value="{{ $l }}" {{ old('education_level')===$l ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex items-center sm:pt-7">
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="eu_resident" value="1" {{ old('eu_resident') ? 'checked' : '' }}
                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            <span class="text-sm">Residente comunitario (UE)</span>
          </label>
        </div>
      </div>

      <button type="submit"
              class="w-full py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition">
        Crear usuario
      </button>

      <p class="text-sm text-center text-slate-500 dark:text-slate-400 mt-3">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Inicia sesión</a>
      </p>
    </form>
  </div>
</body>
</html>
