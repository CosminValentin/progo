@props(['company' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">CIF/NIF <span class="text-rose-600">*</span></label>
    <input name="cif_nif" value="{{ old('cif_nif', $company->cif_nif ?? '') }}" maxlength="16" required
           class="w-full rounded-xl border border-gray-200 bg-white dark:bg-slate-800 dark:border-slate-700 px-3 py-2.5 shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-500">
    @error('cif_nif')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Nombre <span class="text-rose-600">*</span></label>
    <input name="nombre" value="{{ old('nombre', $company->nombre ?? '') }}" maxlength="160" required
           class="w-full rounded-xl border border-gray-200 bg-white dark:bg-slate-800 dark:border-slate-700 px-3 py-2.5 shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-500">
    @error('nombre')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Ámbito</label>
    <input name="ambito" value="{{ old('ambito', $company->ambito ?? '') }}" maxlength="30"
           class="w-full rounded-xl border border-gray-200 bg-white dark:bg-slate-800 dark:border-slate-700 px-3 py-2.5 shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-500">
    @error('ambito')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Actividad</label>
    <input name="actividad" value="{{ old('actividad', $company->actividad ?? '') }}" maxlength="80"
           class="w-full rounded-xl border border-gray-200 bg-white dark:bg-slate-800 dark:border-slate-700 px-3 py-2.5 shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-500">
    @error('actividad')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Contacto (nombre)</label>
    <input name="contacto_nombre" value="{{ old('contacto_nombre', $company->contacto_nombre ?? '') }}" maxlength="120"
           class="w-full rounded-xl border border-gray-200 bg-white dark:bg-slate-800 dark:border-slate-700 px-3 py-2.5 shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-500">
    @error('contacto_nombre')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Contacto (email)</label>
    <input type="email" name="contacto_email" value="{{ old('contacto_email', $company->contacto_email ?? '') }}" maxlength="120"
           class="w-full rounded-xl border border-gray-200 bg-white dark:bg-slate-800 dark:border-slate-700 px-3 py-2.5 shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-500">
    @error('contacto_email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-2">
    <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Contacto (teléfono)</label>
    <input name="contacto_tel" value="{{ old('contacto_tel', $company->contacto_tel ?? '') }}" maxlength="30"
           class="w-full rounded-xl border border-gray-200 bg-white dark:bg-slate-800 dark:border-slate-700 px-3 py-2.5 shadow-inner focus:outline-none focus:ring-2 focus:ring-indigo-500">
    @error('contacto_tel')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
  </div>
</div>
