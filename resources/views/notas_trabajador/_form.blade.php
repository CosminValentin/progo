@php
  /**
   * Helper para manejar valores antiguos, del modelo o por defecto.
   * Compatible con DateTimeInterface y strings de fecha.
   */
  if (!function_exists('oldOrNota')) {
    function oldOrNota($field, $nota, $default = '') {
        $value = old($field);
        if ($value !== null) return $value;

        if ($nota && isset($nota->$field)) {
            $val = $nota->$field;
            if ($val instanceof \DateTimeInterface) {
                return $val->format('Y-m-d\TH:i');
            }
            if (is_string($val) && strtotime($val)) {
                return date('Y-m-d\TH:i', strtotime($val));
            }
            return $val;
        }
        return $default;
    }
  }
  $isModal = $isModal ?? request()->boolean('modal'); // fallback por si olvidas pasar la var
@endphp

@csrf
<div x-data="notaForm()" class="space-y-8">

  {{-- Header vistoso --}}


  {{-- Campos --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Participante --}}
    <div>
      <label class="flex items-center gap-2 text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">
        <i class="fa-solid fa-user text-indigo-600"></i>
        Participante <span class="text-rose-600">*</span>
      </label>
      <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
        <select name="id_participante"
                class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 pl-10 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-sm">
          @foreach($participants as $p)
            <option value="{{ $p->id }}"
              @selected(old('id_participante', $nota->id_participante ?? ($participant->id ?? null)) == $p->id)>
              {{ $p->nombre }}
            </option>
          @endforeach
        </select>
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Selecciona el participante asociado a la nota.</p>
      @error('id_participante') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Fecha y hora --}}
    <div>
      <label class="flex items-center gap-2 text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">
        <i class="fa-solid fa-calendar-day text-indigo-600"></i>
        Fecha y hora <span class="text-rose-600">*</span>
      </label>
      <div class="relative">
        <i class="fa-solid fa-clock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
        <input type="datetime-local" name="fecha_hora"
               value="{{ oldOrNota('fecha_hora', $nota ?? null, now()->format('Y-m-d\TH:i')) }}"
               class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 pl-10 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-sm"/>
      </div>
      <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Cuándo se registró o debe considerarse la nota.</p>
      @error('fecha_hora') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>
  </div>

  {{-- Texto --}}
  <div>
    <label class="flex items-center gap-2 text-sm font-medium mb-2 text-gray-700 dark:text-slate-300">
      <i class="fa-solid fa-align-left text-indigo-600"></i>
      Texto <span class="text-rose-600">*</span>
    </label>

    <div class="relative rounded-2xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-indigo-500">
      <div class="absolute left-3 top-3 text-gray-400 dark:text-slate-500">
        <i class="fa-solid fa-quote-left"></i>
      </div>
      <textarea
        name="texto"
        x-model="texto"
        @input="contar()"
        rows="7"
        class="w-full pl-10 pr-4 py-3 text-sm bg-transparent outline-none resize-y min-h-[160px] dark:text-slate-100"
        placeholder="Escribe aquí el detalle de la nota…">{{ old('texto', $nota->texto ?? '') }}</textarea>

      <div class="flex items-center justify-between px-4 py-2 bg-gray-50/80 dark:bg-slate-700/40 text-xs">
        <div class="text-gray-500 dark:text-slate-400">Mantén la nota clara y accionable.</div>
        <div :class="{'text-rose-500': largo > max, 'text-gray-500 dark:text-slate-400': largo <= max}">
          <span x-text="largo"></span> / <span x-text="max"></span>
        </div>
      </div>
    </div>
    @error('texto') <p class="text-rose-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Estado --}}
{{-- Campo Estado --}}
<div class="mb-4">
  <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Estado</label>
  <select name="estado" id="estado"
          class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-200 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-sm transition">
    <option value="activo" {{ old('estado', $nota?->estado ?? '') === 'activo' ? 'selected' : '' }}>Activo</option>
    <option value="inactivo" {{ old('estado', $nota?->estado ?? '') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
  </select>
  @error('estado')
    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
  @enderror
</div>


  {{-- Footer --}}
  <div class="sticky bottom-4 z-10">
    <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white/90 dark:bg-slate-800/90 backdrop-blur shadow-lg p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
      <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-300">
        <i class="fa-solid fa-circle-info"></i>
        <span>Revisa la información antes de guardar.</span>
      </div>
      <div class="flex items-center gap-3">
        @if($isModal)
          <a href="#" data-modal-cancel
             class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-100 hover:bg-gray-100 dark:hover:bg-slate-600 transition">
            Cerrar
          </a>
        @else
          <a href="{{ route('notas.index') }}"
             class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-700 dark:text-slate-100 hover:bg-gray-100 dark:hover:bg-slate-600 transition">
            Cancelar
          </a>
        @endif

        <button type="submit"
                :disabled="largo > max"
                class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 shadow disabled:opacity-60 disabled:cursor-not-allowed transition">
          @if(isset($nota)) Guardar cambios @else Crear nota @endif
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  function notaForm() {
    return {
      max: 1000,
      texto: @json(old('texto', $nota->texto ?? '')),
      get largo() { return (this.texto || '').length; },
      contar() {},
    }
  }
</script>
