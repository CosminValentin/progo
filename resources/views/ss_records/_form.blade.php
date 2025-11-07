@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <div>
    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">Participante</label>
    <select name="participant_id" class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-2">
      <option value="">— Sin asignar —</option>
      @foreach($participants as $p)
        <option value="{{ $p->id }}" @selected(old('participant_id', $ss->participant_id ?? null) == $p->id)>
          {{ $p->nombre }} ({{ $p->dni_nie }})
        </option>
      @endforeach
    </select>
    @error('participant_id') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">Régimen *</label>
    <input type="text" name="regimen" required
           value="{{ old('regimen', $ss->regimen ?? '') }}"
           placeholder="General / Agrario / Autónomo / alta / ..."
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-2" />
    @error('regimen') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">Días en alta</label>
    <input type="number" name="dias_alta" min="0"
           value="{{ old('dias_alta', $ss->dias_alta ?? '') }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-2" />
    @error('dias_alta') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">Jornadas reales</label>
    <input type="number" name="jornadas_reales" min="0"
           value="{{ old('jornadas_reales', $ss->jornadas_reales ?? '') }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-2" />
    @error('jornadas_reales') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">Coeficiente aplicado</label>
    <input type="number" name="coef_aplicado" step="0.0001" min="0"
           value="{{ old('coef_aplicado', $ss->coef_aplicado ?? '') }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-2" />
    @error('coef_aplicado') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">Días equivalentes</label>
    <input type="number" name="dias_equivalentes" min="0"
           value="{{ old('dias_equivalentes', $ss->dias_equivalentes ?? '') }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-2" />
    @error('dias_equivalentes') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6">
  <label class="block text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">Observaciones</label>
  <textarea name="observaciones" rows="5"
            placeholder="Notas u observaciones relevantes…"
            class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 p-3">{{ old('observaciones', $ss->observaciones ?? '') }}</textarea>
  @error('observaciones') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mt-8 flex gap-3 justify-end">
  <a href="{{ route('ss.index') }}"
     class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700">
    Cancelar
  </a>
  <button class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow">
    Guardar
  </button>
</div>
