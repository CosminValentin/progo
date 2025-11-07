@php
  $currentId = $currentId ?? old('participant_id');
@endphp

<div
  class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow"
  x-data="{
    ownerId: @js($currentId),
    ownerLabel: '',
    setOwner(id, label = '') { this.ownerId = id; this.ownerLabel = label || ('#' + id); },
    clearOwner(){ this.ownerId = null; this.ownerLabel = ''; },
    parseAndSetFromInput(el) {
      const v = (el.value || '').trim();
      const m = v.match(/^#?\s*(\d+)/);
      if (m) { this.setOwner(parseInt(m[1],10), v); }
    }
  }"
>
  <div class="flex items-center justify-between mb-4">
    <div>
      <h3 class="text-sm font-semibold tracking-wide text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
        <i class="fa-regular fa-id-badge"></i> Participante
      </h3>
      <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
        El CV se asocia a un participante. Elige uno de la lista o con los chips rápidos.
      </p>
    </div>

    <div class="flex items-center gap-2">
      <template x-if="ownerId">
        <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200 px-3 py-1 text-xs font-semibold">
          <i class="fa-solid fa-link"></i>
          <span x-text="ownerLabel || ('#' + ownerId)"></span>
        </span>
      </template>
      <button type="button"
              class="px-2.5 py-1 rounded-md text-xs border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 hover:bg-gray-50 dark:hover:bg-slate-700 disabled:opacity-50"
              @click="clearOwner()" :disabled="!ownerId">
        Quitar selección
      </button>
    </div>
  </div>

  <input type="hidden" name="participant_id" :value="ownerId">

  <div class="grid grid-cols-1 gap-3">
    <div>
      <label class="block text-xs font-medium mb-1 text-gray-700 dark:text-slate-300">Buscar/filtrar</label>
      <input list="participantsList"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
             placeholder="Escribe para filtrar… p. ej. '6 — Ana Pérez'"
             @change="parseAndSetFromInput($event.target)">
      <datalist id="participantsList">
        @foreach($participants as $p)
          <option value="{{ $p->id }} — {{ $p->nombre }}"></option>
        @endforeach
      </datalist>
      <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Elige de la lista; el ID se guardará automáticamente.</p>
      @error('participant_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="block text-xs font-medium mb-2 text-gray-700 dark:text-slate-300">Selección rápida</label>
      <div class="flex flex-wrap gap-2 max-h-56 overflow-auto thin-scrollbar">
        @foreach($participants as $p)
          <button type="button"
                  class="px-3 py-1.5 rounded-full border text-xs transition
                         border-gray-200 dark:border-slate-600
                         bg-white dark:bg-slate-900
                         hover:bg-indigo-50 dark:hover:bg-slate-700
                         focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  :class="ownerId==={{ $p->id }} ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-slate-700/60' : ''"
                  @click="setOwner({{ $p->id }}, '#{{ $p->id }} — {{ addslashes($p->nombre) }}')">
            #{{ $p->id }} {{ $p->nombre }}
          </button>
        @endforeach
      </div>
    </div>
  </div>
</div>
