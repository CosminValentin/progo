@props(['company' => null])

<!-- Identificación -->
<div class="rounded-xl border border-indigo-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-indigo-700 dark:text-indigo-300 mb-4">
    <i class="fa-regular fa-id-badge mr-2"></i> Identificación
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">CIF/NIF <span class="text-rose-600">*</span></label>
      <input name="cif_nif" value="{{ old('cif_nif', $company->cif_nif ?? '') }}" maxlength="20" required
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
      @error('cif_nif')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Nombre <span class="text-rose-600">*</span></label>
      <input name="nombre" value="{{ old('nombre', $company->nombre ?? '') }}" maxlength="160" required
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
      @error('nombre')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>

<!-- Empresa -->
<div class="mt-5 rounded-xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-emerald-700 dark:text-emerald-300 mb-4">
    <i class="fa-regular fa-building mr-2"></i> Empresa
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-1">
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Ámbito</label>
      <input name="ambito" value="{{ old('ambito', $company->ambito ?? '') }}" maxlength="80"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-emerald-500">
      @error('ambito')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Actividad</label>
      <input name="actividad" value="{{ old('actividad', $company->actividad ?? '') }}" maxlength="160"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-emerald-500">
      @error('actividad')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>

<!-- Contacto -->
<div class="mt-5 rounded-xl border border-sky-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-sky-700 dark:text-sky-300 mb-4">
    <i class="fa-regular fa-envelope mr-2"></i> Contacto
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Nombre</label>
      <input name="contacto_nombre" value="{{ old('contacto_nombre', $company->contacto_nombre ?? '') }}" maxlength="120"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-sky-500">
      @error('contacto_nombre')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Email</label>
      <input type="email" name="contacto_email" value="{{ old('contacto_email', $company->contacto_email ?? '') }}" maxlength="120"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-sky-500">
      @error('contacto_email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Teléfono</label>
      <input name="contacto_tel" value="{{ old('contacto_tel', $company->contacto_tel ?? '') }}" maxlength="30"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-sky-500">
      @error('contacto_tel')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>
