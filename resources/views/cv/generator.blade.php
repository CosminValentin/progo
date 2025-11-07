@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Generar CV</h1>
      <p class="text-sm text-gray-600 dark:text-slate-400">Completa los campos y genera un CV imprimible con foto, idiomas, redes y más</p>
    </div>
    <button form="cvGenForm"
            class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow">
      <i class="fa-solid fa-eye"></i> Previsualizar
    </button>
  </div>
@endsection

@section('content')
  @if($errors->any())
    <div class="mb-6 rounded-xl border border-rose-300 bg-rose-50 p-4 text-rose-800 dark:border-rose-600 dark:bg-rose-900 dark:text-rose-100 shadow">
      {{ $errors->first() }}
    </div>
  @endif

  <form id="cvGenForm" method="POST" action="{{ route('cv.generate.preview') }}" enctype="multipart/form-data" class="space-y-8">
    @csrf

    {{-- Participante + título + foto --}}
    <div class="rounded-2xl p-6 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
      <h2 class="text-lg font-semibold mb-4">Identificación</h2>
      <div class="grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
          <label class="block text-sm font-medium mb-1">Participante *</label>
          <select name="participant_id" required
                  class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
            <option value="">Seleccionar…</option>
            @foreach($participants as $pp)
              <option value="{{ $pp->id }}">#{{ $pp->id }} — {{ $pp->nombre }} ({{ $pp->dni_nie }})</option>
            @endforeach
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Foto (JPG/PNG/WEBP, máx 2MB)</label>
          <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp"
                 class="block w-full text-sm file:mr-4 file:px-3 file:py-2 file:rounded-lg file:border-0
                        file:bg-indigo-600 file:text-white hover:file:bg-indigo-700
                        rounded-xl border border-gray-300 dark:border-slate-600
                        bg-white dark:bg-slate-800 px-3 py-2">
        </div>

        <div class="md:col-span-3">
          <label class="block text-sm font-medium mb-1">Título profesional</label>
          <input type="text" name="titulo" placeholder="Ej: Desarrollador Full Stack"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
      </div>
    </div>

    {{-- Contacto y redes --}}
    <div class="rounded-2xl p-6 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
      <h2 class="text-lg font-semibold mb-4">Contacto y redes</h2>
      <div class="grid md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input name="email" type="email" placeholder="usuario@correo.com"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Teléfono</label>
          <input name="telefono" type="text" placeholder="+34 600 000 000"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Dirección</label>
          <input name="direccion" type="text" placeholder="Calle, ciudad, provincia"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Fecha de nacimiento</label>
          <input name="fecha_nac" type="date"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">LinkedIn</label>
          <input name="linkedin" type="url" placeholder="https://www.linkedin.com/in/usuario"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">GitHub</label>
          <input name="github" type="url" placeholder="https://github.com/usuario"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
        </div>
        <div class="md:col-span-3">
          <label class="block text-sm font-medium mb-1">Web personal / Portafolio</label>
          <input name="web" type="url" placeholder="https://tu-dominio.com"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
        </div>
      </div>
    </div>

    {{-- Resumen --}}
    <div class="rounded-2xl p-6 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
      <h2 class="text-lg font-semibold mb-4">Resumen profesional</h2>
      <textarea name="resumen" rows="4" placeholder="Breve descripción de experiencia y fortalezas…"
                class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
    </div>

    {{-- Habilidades (chips) --}}
    <div class="rounded-2xl p-6 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
      <h2 class="text-lg font-semibold mb-2">Habilidades</h2>
      <textarea name="habilidades" rows="2" placeholder="Ej: Laravel, PHP 8, MySQL, Tailwind, Git… (separa por comas)"
                class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2"></textarea>
      <p class="text-xs text-gray-500 mt-1">Se mostrarán como chips.</p>
    </div>

    {{-- Idiomas (5 filas) --}}
    <div class="rounded-2xl p-6 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
      <h2 class="text-lg font-semibold mb-4">Idiomas</h2>
      @for($i=0;$i<5;$i++)
        <div class="grid md:grid-cols-2 gap-4 mb-3">
          <input name="lang[{{$i}}][nombre]" placeholder="Idioma (p.ej., Inglés)"
                 class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
          <select name="lang[{{$i}}][nivel]"
                  class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
            <option value="">Nivel…</option>
            <option value="1">A1</option>
            <option value="2">A2</option>
            <option value="3">B1</option>
            <option value="4">B2</option>
            <option value="5">C1/C2</option>
          </select>
        </div>
      @endfor
    </div>

    {{-- Experiencia (5 entradas) --}}
    <div class="rounded-2xl p-6 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
      <h2 class="text-lg font-semibold mb-4">Experiencia</h2>
      @for($i=0;$i<5;$i++)
        <div class="grid md:grid-cols-3 gap-4 mb-3">
          <input name="exp[{{$i}}][puesto]"  placeholder="Puesto"
                 class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
          <input name="exp[{{$i}}][empresa]" placeholder="Empresa"
                 class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
          <input name="exp[{{$i}}][fecha]"   placeholder="Fechas (2022–2024)"
                 class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
        </div>
        <textarea name="exp[{{$i}}][desc]" rows="2" placeholder="Descripción / logros…"
                  class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 mb-6"></textarea>
      @endfor
    </div>

    {{-- Formación (5 entradas) --}}
    <div class="rounded-2xl p-6 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
      <h2 class="text-lg font-semibold mb-4">Formación</h2>
      @for($i=0;$i<5;$i++)
        <div class="grid md:grid-cols-3 gap-4 mb-4">
          <input name="edu[{{$i}}][titulo]" placeholder="Titulación / Certificación"
                 class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
          <input name="edu[{{$i}}][centro]" placeholder="Centro"
                 class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
          <input name="edu[{{$i}}][fecha]"  placeholder="Fechas"
                 class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
        </div>
      @endfor
    </div>

    {{-- Proyectos (5 entradas) --}}
    <div class="rounded-2xl p-6 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
      <h2 class="text-lg font-semibold mb-4">Proyectos</h2>
      @for($i=0;$i<5;$i++)
        <div class="grid md:grid-cols-3 gap-4 mb-3">
          <input name="proy[{{$i}}][titulo]" placeholder="Título del proyecto"
                 class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
          <input name="proy[{{$i}}][link]"   placeholder="URL (opcional)"
                 class="rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2">
          <div></div>
        </div>
        <textarea name="proy[{{$i}}][desc]" rows="2" placeholder="Descripción breve…"
                  class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 mb-6"></textarea>
      @endfor
    </div>

    {{-- Intereses --}}
    <div class="rounded-2xl p-6 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
      <h2 class="text-lg font-semibold mb-2">Intereses</h2>
      <textarea name="intereses" rows="2" placeholder="Ej: Lectura, viajes, fotografía… (separa por comas)"
                class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2"></textarea>
    </div>

    <div class="flex justify-end gap-3">
      <a href="{{ url()->previous() }}"
         class="px-5 py-3 rounded-lg border border-gray-300 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700">Cancelar</a>
      <button class="px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow">
        Previsualizar CV
      </button>
    </div>
  </form>
@endsection
