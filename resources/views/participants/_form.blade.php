@props(['participant' => null])

<div class="rounded-xl border border-indigo-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-indigo-700 dark:text-indigo-300 mb-4">
    <i class="fa-regular fa-id-badge mr-2"></i> Identificación
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">DNI/NIE <span class="text-rose-600">*</span></label>
      <input name="dni_nie" value="{{ old('dni_nie', $participant->dni_nie ?? '') }}" maxlength="16" required
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
      @error('dni_nie')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Nombre <span class="text-rose-600">*</span></label>
      <input name="nombre" value="{{ old('nombre', $participant->nombre ?? '') }}" maxlength="120" required
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
      @error('nombre')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>

<div class="mt-5 rounded-xl border border-sky-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-sky-700 dark:text-sky-300 mb-4">
    <i class="fa-regular fa-envelope mr-2"></i> Contacto
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Email</label>
      <input type="email" name="email" value="{{ old('email', $participant->email ?? '') }}" maxlength="120"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-sky-500">
      @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Teléfono</label>
      <input name="telefono" value="{{ old('telefono', $participant->telefono ?? '') }}" maxlength="30"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-sky-500">
      @error('telefono')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>

<div class="mt-5 rounded-xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-emerald-700 dark:text-emerald-300 mb-4">
    <i class="fa-regular fa-calendar mr-2"></i> Programa
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Fecha alta <span class="text-rose-600">*</span></label>
      <input type="date" name="fecha_alta_prog"
             value="{{ old('fecha_alta_prog', optional($participant->fecha_alta_prog ?? null)->format('Y-m-d')) }}" required
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-emerald-500">
      @error('fecha_alta_prog')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Provincia</label>
      <input name="provincia" value="{{ old('provincia', $participant->provincia ?? '') }}" maxlength="40"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-emerald-500">
      @error('provincia')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Estado</label>
      <input name="estado" value="{{ old('estado', $participant->estado ?? 'activo') }}" maxlength="20"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-emerald-500">
      @error('estado')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>

<div class="mt-5 rounded-xl border border-fuchsia-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-fuchsia-700 dark:text-fuchsia-300 mb-4">
    <i class="fa-regular fa-note-sticky mr-2"></i> Notas
  </h3>
  <textarea name="notas" rows="4"
            class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-fuchsia-500"
            placeholder="Observaciones, detalles…">{{ old('notas', $participant->notas ?? '') }}</textarea>
  @error('notas')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
