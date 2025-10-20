@props(['company' => null])

{{-- Sección de Identificación --}}
<div class="rounded-xl border border-indigo-300 bg-white dark:bg-slate-800 p-6 shadow-lg">
  <h3 class="text-sm font-bold tracking-wide text-indigo-700 dark:text-indigo-300 mb-4 uppercase">
    <i class="fa-regular fa-building mr-2"></i> Identificación
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-200 mb-1">CIF/NIF <span class="text-rose-600">*</span></label>
      <input name="cif_nif" value="{{ old('cif_nif', $company->cif_nif ?? '') }}" maxlength="16" required
             class="w-full rounded-lg border border-indigo-200 dark:border-indigo-500 bg-white dark:bg-slate-900 px-4 py-3 text-sm shadow focus:ring-2 focus:ring-indigo-500 transition">
      @error('cif_nif')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-200 mb-1">Nombre <span class="text-rose-600">*</span></label>
      <input name="nombre" value="{{ old('nombre', $company->nombre ?? '') }}" maxlength="160" required
             class="w-full rounded-lg border border-indigo-200 dark:border-indigo-500 bg-white dark:bg-slate-900 px-4 py-3 text-sm shadow focus:ring-2 focus:ring-indigo-500 transition">
      @error('nombre')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>

{{-- Sección de Detalles --}}
<div class="mt-6 rounded-xl border border-sky-300 bg-white dark:bg-slate-800 p-6 shadow-lg">
  <h3 class="text-sm font-bold tracking-wide text-sky-700 dark:text-sky-300 mb-4 uppercase">
    <i class="fa-regular fa-rectangle-list mr-2"></i> Detalles
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-200 mb-1">Ámbito</label>
      <input name="ambito" value="{{ old('ambito', $company->ambito ?? '') }}" maxlength="30"
             class="w-full rounded-lg border border-sky-200 dark:border-sky-500 bg-white dark:bg-slate-900 px-4 py-3 text-sm shadow focus:ring-2 focus:ring-sky-500 transition">
      @error('ambito')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-200 mb-1">Actividad</label>
      <input name="actividad" value="{{ old('actividad', $company->actividad ?? '') }}" maxlength="80"
             class="w-full rounded-lg border border-sky-200 dark:border-sky-500 bg-white dark:bg-slate-900 px-4 py-3 text-sm shadow focus:ring-2 focus:ring-sky-500 transition">
      @error('actividad')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>

{{-- Sección de Contacto --}}
<div class="mt-6 rounded-xl border border-emerald-300 bg-white dark:bg-slate-800 p-6 shadow-lg">
  <h3 class="text-sm font-bold tracking-wide text-emerald-700 dark:text-emerald-300 mb-4 uppercase">
    <i class="fa-regular fa-address-book mr-2"></i> Contacto
  </h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-200 mb-1">Nombre</label>
      <input name="contacto_nombre" value="{{ old('contacto_nombre', $company->contacto_nombre ?? '') }}" maxlength="120"
             class="w-full rounded-lg border border-emerald-200 dark:border-emerald-500 bg-white dark:bg-slate-900 px-4 py-3 text-sm shadow focus:ring-2 focus:ring-emerald-500 transition">
      @error('contacto_nombre')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-200 mb-1">Email</label>
      <input type="email" name="contacto_email" value="{{ old('contacto_email', $company->contacto_email ?? '') }}" maxlength="120"
             class="w-full rounded-lg border border-emerald-200 dark:border-emerald-500 bg-white dark:bg-slate-900 px-4 py-3 text-sm shadow focus:ring-2 focus:ring-emerald-500 transition">
      @error('contacto_email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-slate-200 mb-1">Teléfono</label>
      <input name="contacto_tel" value="{{ old('contacto_tel', $company->contacto_tel ?? '') }}" maxlength="30"
             class="w-full rounded-lg border border-emerald-200 dark:border-emerald-500 bg-white dark:bg-slate-900 px-4 py-3 text-sm shadow focus:ring-2 focus:ring-emerald-500 transition">
      @error('contacto_tel')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>
