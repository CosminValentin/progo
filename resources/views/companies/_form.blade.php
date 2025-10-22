@props(['company' => null])

<!-- Identificación -->
<div class="rounded-xl border border-indigo-200 bg-white p-6 shadow-lg dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-indigo-700 dark:text-indigo-300 mb-6 flex items-center">
    <i class="fa-regular fa-id-badge mr-3"></i> Identificación
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-2">CIF/NIF <span class="text-rose-600">*</span></label>
      <input name="cif_nif" value="{{ old('cif_nif', $company->cif_nif ?? '') }}" maxlength="30" required
             class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none">
      @error('cif_nif')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-2">Nombre <span class="text-rose-600">*</span></label>
      <input name="nombre" value="{{ old('nombre', $company->nombre ?? '') }}" maxlength="160" required
             class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none">
      @error('nombre')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>

<!-- Empresa -->
<div class="mt-6 rounded-xl border border-emerald-200 bg-white p-6 shadow-lg dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-emerald-700 dark:text-emerald-300 mb-6 flex items-center">
    <i class="fa-regular fa-building mr-3"></i> Empresa
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-2">Ámbito</label>
      <input name="ambito" value="{{ old('ambito', $company->ambito ?? '') }}" maxlength="80"
             class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none">
      @error('ambito')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div class="md:col-span-2">
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-2">Actividad</label>
      <input name="actividad" value="{{ old('actividad', $company->actividad ?? '') }}" maxlength="160"
             class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 shadow-inner focus:ring-2 focus:ring-emerald-500 focus:outline-none">
      @error('actividad')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>

<!-- Contacto -->
<div class="mt-6 rounded-xl border border-sky-200 bg-white p-6 shadow-lg dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-sky-700 dark:text-sky-300 mb-6 flex items-center">
    <i class="fa-regular fa-envelope mr-3"></i> Contacto
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-2">Nombre</label>
      <input name="contacto_nombre" value="{{ old('contacto_nombre', $company->contacto_nombre ?? '') }}" maxlength="120"
             class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 shadow-inner focus:ring-2 focus:ring-sky-500 focus:outline-none">
      @error('contacto_nombre')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-2">Email</label>
      <input type="email" name="contacto_email" value="{{ old('contacto_email', $company->contacto_email ?? '') }}" maxlength="120"
             class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 shadow-inner focus:ring-2 focus:ring-sky-500 focus:outline-none">
      @error('contacto_email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-2">Teléfono</label>
      <input name="contacto_tel" value="{{ old('contacto_tel', $company->contacto_tel ?? '') }}" maxlength="30"
             class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 shadow-inner focus:ring-2 focus:ring-sky-500 focus:outline-none">
      @error('contacto_tel')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>
