@props(['check' => null, 'participants' => collect()])

@php
  $oldOr = function($key, $fallback = '') use ($check) {
    return old($key, $check->{$key} ?? $fallback);
  };
@endphp

<div class="rounded-xl border border-indigo-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-indigo-700 dark:text-indigo-300 mb-5 flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> Datos del registro
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Participante</label>
      <select name="participant_id"
              class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
        <option value="">— Sin participante —</option>
        @foreach($participants as $p)
          <option value="{{ $p->id }}" @selected($oldOr('participant_id')==$p->id)>
            #{{ $p->id }} — {{ $p->nombre }}
          </option>
        @endforeach
      </select>
      @error('participant_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Fecha</label>
      <input type="datetime-local" name="fecha"
             value="{{ old('fecha', isset($check) && $check->fecha ? $check->fecha->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      @error('fecha') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>
  </div>
</div>

<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-5">
  <div class="rounded-xl border border-sky-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
    <h4 class="text-sm font-semibold text-sky-700 dark:text-sky-300 mb-4 flex items-center gap-2">
      <i class="fa-solid fa-hourglass-half"></i> Periodo
    </h4>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="periodo_valido" value="1" @checked((bool)$oldOr('periodo_valido',0))
             class="rounded border-gray-300 dark:border-slate-600">
      <span class="text-sm">Periodo válido</span>
    </label>
    <div class="mt-3">
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Días válidos</label>
      <input type="number" name="dias_validos" min="0" value="{{ $oldOr('dias_validos') }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-sky-500 focus:outline-none transition">
      @error('dias_validos') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="rounded-xl border border-amber-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
    <h4 class="text-sm font-semibold text-amber-700 dark:text-amber-300 mb-4 flex items-center gap-2">
      <i class="fa-solid fa-database"></i> Fuente & Parcialidad
    </h4>
    <div class="mb-3">
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Fuente</label>
      <input type="text" name="fuente" maxlength="20" value="{{ $oldOr('fuente') }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-amber-500 focus:outline-none transition" placeholder="p.ej. 'VidasLab'">
      @error('fuente') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Parcialidad (%)</label>
      <input type="number" name="parcialidad" min="0" max="100" value="{{ $oldOr('parcialidad') }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-amber-500 focus:outline-none transition" placeholder="0–100">
      @error('parcialidad') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>
  </div>

  <div class="rounded-xl border border-emerald-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
    <h4 class="text-sm font-semibold text-emerald-700 dark:text-emerald-300 mb-4 flex items-center gap-2">
      <i class="fa-solid fa-circle-check"></i> Validación 90 días
    </h4>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="valido_90_dias" value="1" @checked((bool)$oldOr('valido_90_dias',0))
             class="rounded border-gray-300 dark:border-slate-600">
      <span class="text-sm">Válido 90 días</span>
    </label>
  </div>
</div>

<div class="mt-6 rounded-xl border border-fuchsia-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h4 class="text-sm font-semibold text-fuchsia-700 dark:text-fuchsia-300 mb-4 flex items-center gap-2">
    <i class="fa-regular fa-note-sticky"></i> Observaciones
  </h4>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Observaciones</label>
      <textarea name="observaciones" rows="4"
                class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-fuchsia-500 focus:outline-none transition">{{ $oldOr('observaciones') }}</textarea>
      @error('observaciones') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Observaciones (internas)</label>
      <textarea name="observaciones2" rows="4"
                class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-fuchsia-500 focus:outline-none transition">{{ $oldOr('observaciones2') }}</textarea>
      @error('observaciones2') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
    </div>
  </div>
</div>
