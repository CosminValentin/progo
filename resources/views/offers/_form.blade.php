@props(['offer' => null, 'companies' => collect()])

<div class="rounded-xl border border-indigo-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-indigo-700 dark:text-indigo-300 mb-4">
    <i class="fa-regular fa-id-badge mr-2"></i> Datos de la oferta
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Empresa <span class="text-rose-600">*</span></label>
      <select name="company_id" required
              class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
        <option value="">— Selecciona —</option>
        @foreach($companies as $c)
          <option value="{{ $c->id }}" {{ (string)old('company_id', $offer->company_id ?? '') === (string)$c->id ? 'selected' : '' }}>
            {{ $c->nombre }} — {{ $c->cif_nif }}
          </option>
        @endforeach
      </select>
      @error('company_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Puesto <span class="text-rose-600">*</span></label>
      <input name="puesto" value="{{ old('puesto', $offer->puesto ?? '') }}" maxlength="160" required
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
      @error('puesto')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Tipo de contrato</label>
      <input name="tipo_contrato" value="{{ old('tipo_contrato', $offer->tipo_contrato ?? '') }}" maxlength="80"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-emerald-500">
      @error('tipo_contrato')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">% Jornada</label>
      <input type="number" name="jornada_pct" min="1" max="100"
             value="{{ old('jornada_pct', $offer->jornada_pct ?? '') }}"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-emerald-500">
      @error('jornada_pct')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Ubicación</label>
      <input name="ubicacion" value="{{ old('ubicacion', $offer->ubicacion ?? '') }}" maxlength="160"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-sky-500">
      @error('ubicacion')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Requisitos</label>
      <textarea name="requisitos" rows="4"
                class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-fuchsia-500"
                placeholder="Formación, experiencia, carnets, idiomas…">{{ old('requisitos', $offer->requisitos ?? '') }}</textarea>
      @error('requisitos')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Estado</label>
      <input name="estado" value="{{ old('estado', $offer->estado ?? 'abierta') }}" maxlength="30"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
      @error('estado')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Fecha <span class="text-rose-600">*</span></label>
      <input type="date" name="fecha" required
             value="{{ old('fecha', optional($offer->fecha ?? null)->format('Y-m-d')) }}"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
      @error('fecha')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>
