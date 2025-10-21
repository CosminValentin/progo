@props(['application' => null, 'participants' => collect(), 'offers' => collect(), 'estados' => []])

<div class="rounded-xl border border-indigo-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-indigo-700 dark:text-indigo-300 mb-4">
    <i class="fa-regular fa-id-badge mr-2"></i> Datos de la candidatura
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Participante <span class="text-rose-600">*</span></label>
      <select name="participant_id" required
              class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
        <option value="">— Selecciona —</option>
        @foreach($participants as $p)
          <option value="{{ $p->id }}" {{ (string)old('participant_id', $application->participant_id ?? '') === (string)$p->id ? 'selected' : '' }}>
            {{ $p->nombre }} — {{ $p->dni_nie }}
          </option>
        @endforeach
      </select>
      @error('participant_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Oferta <span class="text-rose-600">*</span></label>
      <select name="offer_id" required
              class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
        <option value="">— Selecciona —</option>
        @foreach($offers as $o)
          @php
            $label = $o->titulo ?? $o->nombre ?? ('Oferta #'.$o->id);
          @endphp
          <option value="{{ $o->id }}" {{ (string)old('offer_id', $application->offer_id ?? '') === (string)$o->id ? 'selected' : '' }}>
            {{ $label }}
          </option>
        @endforeach
      </select>
      @error('offer_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Estado <span class="text-rose-600">*</span></label>
      <select name="estado" required
              class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
        @foreach($estados as $e)
          <option value="{{ $e }}" {{ old('estado', $application->estado ?? '') === $e ? 'selected' : '' }}>
            {{ ucfirst(str_replace('_',' ',$e)) }}
          </option>
        @endforeach
      </select>
      @error('estado')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-xs font-medium text-gray-600 dark:text-slate-300 mb-1">Fecha <span class="text-rose-600">*</span></label>
      <input type="date" name="fecha" required
             value="{{ old('fecha', optional($application->fecha ?? null)->format('Y-m-d')) }}"
             class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500">
      @error('fecha')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
  </div>
</div>
