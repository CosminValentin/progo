<div
  class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow"
  x-data="{
    ownerType: @js(old('owner_type')) || '',
    ownerId: @js(old('owner_id')) || null,
    ownerLabel: '',
    setOwner(id, label = '') {
      this.ownerId = id;
      this.ownerLabel = label || ('#' + id);
    },
    clearOwner(){
      this.ownerId = null;
      this.ownerLabel = '';
    },
    setType(t){
      if (this.ownerType !== t){ this.clearOwner(); }
      this.ownerType = t;
    },
    parseAndSetFromInput(el) {
      const v = (el.value || '').trim();
      const m = v.match(/^#?\s*(\d+)/);
      if (m) { this.setOwner(parseInt(m[1],10), v); }
    }
  }"
>
  <!-- Header + selección actual -->
  <div class="flex items-center justify-between mb-4">
    <div>
      <h3 class="text-sm font-semibold tracking-wide text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
        <i class="fa-solid fa-user-tag"></i> Propietario (opcional)
      </h3>
      <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
        Asocia el documento a un participante, empresa, oferta o usuario.
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
              @click="clearOwner()" :disabled="!ownerId" title="Quitar propietario">
        <i class="fa-solid fa-xmark mr-1"></i> Quitar
      </button>
    </div>
  </div>

  <!-- Tabs de tipo -->
  <div class="flex flex-wrap items-center gap-2 mb-4">
    <button type="button"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs border transition
                   border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900
                   hover:bg-indigo-50 dark:hover:bg-slate-700"
            :class="ownerType==='' ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-slate-700/60' : ''"
            @click="setType('')">
      <i class="fa-regular fa-circle me-1"></i> Sin propietario
    </button>

    <button type="button"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs border transition
                   border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900
                   hover:bg-indigo-50 dark:hover:bg-slate-700"
            :class="ownerType==='participants' ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-slate-700/60' : ''"
            @click="setType('participants')">
      <i class="fa-solid fa-user me-1"></i> Participante
    </button>

    <button type="button"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs border transition
                   border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900
                   hover:bg-emerald-50 dark:hover:bg-slate-700"
            :class="ownerType==='companies' ? 'ring-2 ring-emerald-500 bg-emerald-50 dark:bg-slate-700/60' : ''"
            @click="setType('companies')">
      <i class="fa-solid fa-building me-1"></i> Empresa
    </button>

    <button type="button"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs border transition
                   border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900
                   hover:bg-amber-50 dark:hover:bg-slate-700"
            :class="ownerType==='offers' ? 'ring-2 ring-amber-500 bg-amber-50 dark:bg-slate-700/60' : ''"
            @click="setType('offers')">
      <i class="fa-solid fa-briefcase me-1"></i> Oferta
    </button>

    <button type="button"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs border transition
                   border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900
                   hover:bg-fuchsia-50 dark:hover:bg-slate-700"
            :class="ownerType==='users' ? 'ring-2 ring-fuchsia-500 bg-fuchsia-50 dark:bg-slate-700/60' : ''"
            @click="setType('users')">
      <i class="fa-solid fa-user-shield me-1"></i> Usuario
    </button>
  </div>

  <!-- Campos reales que viajan al servidor -->
  <input type="hidden" name="owner_type" :value="ownerType">
  <input type="hidden" name="owner_id" :value="ownerId">

  <!-- PARTICIPANTES -->
  <div class="md:col-span-2" x-show="ownerType==='participants'" x-cloak>
    <label class="block text-xs font-medium mb-1 text-gray-700 dark:text-slate-300">
      <i class="fa-solid fa-magnifying-glass me-1"></i> Buscar participante
    </label>

    <div class="relative">
      <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500 text-sm"></i>
      <input list="participantsList"
             class="w-full pl-9 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
             placeholder="Escribe para filtrar… p. ej. '6 — Ana Pérez'"
             @change="parseAndSetFromInput($event.target)">
      <datalist id="participantsList">
        @foreach($participants as $p)
          <option value="{{ $p->id }} — {{ $p->nombre }}"></option>
        @endforeach
      </datalist>
      <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
        Elige de la lista; el ID se guardará automáticamente.
      </p>
    </div>

    <div class="mt-3 flex flex-wrap gap-2">
      @foreach($participants as $p)
        <button type="button"
                class="px-3 py-1.5 rounded-full border text-xs transition
                       border-gray-200 dark:border-slate-600
                       bg-white dark:bg-slate-900
                       hover:bg-indigo-50 dark:hover:bg-slate-700
                       focus:outline-none focus:ring-2 focus:ring-indigo-500"
                :class="ownerId==={{ $p->id }} && ownerType==='participants' ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-slate-700/60' : ''"
                @click="setOwner({{ $p->id }}, '#{{ $p->id }} — {{ addslashes($p->nombre) }}')">
          <i class="fa-solid fa-user me-1 opacity-70"></i>
          #{{ $p->id }} {{ $p->nombre }}
        </button>
      @endforeach
    </div>
    @error('owner_id') <p class="text-rose-600 text-xs mt-2">{{ $message }}</p> @enderror
  </div>

  <!-- EMPRESAS -->
  <div class="md:col-span-2" x-show="ownerType==='companies'" x-cloak>
    <label class="block text-xs font-medium mb-1 text-gray-700 dark:text-slate-300">
      <i class="fa-solid fa-building me-1"></i> Selecciona empresa
    </label>
    <div class="flex flex-wrap gap-2">
      @foreach($companies as $c)
        <button type="button"
                class="px-3 py-1.5 rounded-full border text-xs transition
                       border-gray-200 dark:border-slate-600
                       bg-white dark:bg-slate-900
                       hover:bg-emerald-50 dark:hover:bg-slate-700
                       focus:outline-none focus:ring-2 focus:ring-emerald-500"
                :class="ownerId==={{ $c->id }} && ownerType==='companies' ? 'ring-2 ring-emerald-500 bg-emerald-50 dark:bg-slate-700/60' : ''"
                @click="setOwner({{ $c->id }}, '#{{ $c->id }} — {{ addslashes($c->nombre) }}')">
          <i class="fa-solid fa-industry me-1 opacity-70"></i>
          #{{ $c->id }} {{ $c->nombre }}
        </button>
      @endforeach
    </div>
  </div>

  <!-- OFERTAS -->
  <div class="md:col-span-2" x-show="ownerType==='offers'" x-cloak>
    <label class="block text-xs font-medium mb-1 text-gray-700 dark:text-slate-300">
      <i class="fa-solid fa-briefcase me-1"></i> Selecciona oferta
    </label>
    <div class="flex flex-wrap gap-2">
      @foreach($offers as $o)
        <button type="button"
                class="px-3 py-1.5 rounded-full border text-xs transition
                       border-gray-200 dark:border-slate-600
                       bg-white dark:bg-slate-900
                       hover:bg-amber-50 dark:hover:bg-slate-700
                       focus:outline-none focus:ring-2 focus:ring-amber-500"
                :class="ownerId==={{ $o->id }} && ownerType==='offers' ? 'ring-2 ring-amber-500 bg-amber-50 dark:bg-slate-700/60' : ''"
                @click="setOwner({{ $o->id }}, '#{{ $o->id }} — {{ addslashes($o->puesto) }}')">
          <i class="fa-solid fa-id-badge me-1 opacity-70"></i>
          #{{ $o->id }} {{ $o->puesto }}
        </button>
      @endforeach
    </div>
  </div>

  <!-- USUARIOS (placeholder) -->
  <div class="md:col-span-2" x-show="ownerType==='users'" x-cloak>
    <div class="rounded-lg border border-dashed border-gray-300 dark:border-slate-600 p-4 text-sm text-gray-600 dark:text-slate-300 flex items-center gap-3">
      <i class="fa-solid fa-user-shield"></i>
      Próximamente: selector de usuarios. De momento, puedes dejar el documento sin propietario o elegir otro tipo.
    </div>
  </div>
</div>
