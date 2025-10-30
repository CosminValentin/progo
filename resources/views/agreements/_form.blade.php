@php
  /** @var \App\Models\Agreement|null $agreement */
  $a = $agreement ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  {{-- Empresa --}}
  <div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-200 mb-1">
      <i class="fa-solid fa-building text-indigo-500"></i>
      Empresa <span class="text-rose-600">*</span>
    </label>
    <select name="company_id"
            class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
            required>
      <option value="">— Selecciona empresa —</option>
      @foreach($companies as $c)
        <option value="{{ $c->id }}" @selected(old('company_id', $a->company_id ?? '') == $c->id)>
          {{ $c->nombre }}
        </option>
      @endforeach
    </select>
    @error('company_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Fecha de firma --}}
  <div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-200 mb-1">
      <i class="fa-solid fa-calendar-check text-indigo-500"></i>
      Fecha de firma <span class="text-rose-600">*</span>
    </label>
    <input type="date" name="fecha_firma"
           value="{{ old('fecha_firma', optional($a?->fecha_firma)->format('Y-m-d')) }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
           required>
    @error('fecha_firma') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Estado de firmas --}}
  <div class="flex flex-wrap items-center gap-6 md:col-span-2">
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="firmado_agencia" value="1"
             @checked((int) old('firmado_agencia', (int)($a->firmado_agencia ?? 0)) === 1)
             class="rounded border-gray-300 dark:border-slate-600">
      <span class="text-sm flex items-center gap-1">
        <i class="fa-solid fa-pen-fancy text-indigo-500"></i> Firmado Agencia
      </span>
    </label>

    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="firmado_empresa" value="1"
             @checked((int) old('firmado_empresa', (int)($a->firmado_empresa ?? 0)) === 1)
             class="rounded border-gray-300 dark:border-slate-600">
      <span class="text-sm flex items-center gap-1">
        <i class="fa-solid fa-building-circle-check text-green-500"></i> Firmado Empresa
      </span>
    </label>
  </div>

  {{-- Validez desde --}}
  <div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-200 mb-1">
      <i class="fa-solid fa-calendar-day text-indigo-500"></i>
      Validez desde
    </label>
    <input type="date" name="validez_desde"
           value="{{ old('validez_desde', optional($a?->validez_desde)->format('Y-m-d')) }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
    @error('validez_desde') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Validez hasta --}}
  <div>
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-200 mb-1">
      <i class="fa-solid fa-calendar-xmark text-indigo-500"></i>
      Validez hasta
    </label>
    <input type="date" name="validez_hasta"
           value="{{ old('validez_hasta', optional($a?->validez_hasta)->format('Y-m-d')) }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
    @error('validez_hasta') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Separador --}}
  <div class="md:col-span-2">
    <hr class="border-gray-200 dark:border-slate-700 my-4">
  </div>

  {{-- Documento --}}
  <div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-200 mb-1 flex items-center gap-1">
      <i class="fa-solid fa-file-pdf text-rose-500"></i>
      PDF (documento)
      <span class="text-xs text-gray-400 dark:text-slate-400">(opcional)</span>
    </label>

    <select name="pdf_doc_id"
            class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      <option value="">— Sin documento —</option>
      @foreach($documents as $d)
        <option value="{{ $d->id }}" @selected(old('pdf_doc_id', $a->pdf_doc_id ?? '') == $d->id)>
          📄 #{{ $d->id }} — {{ $d->nombre_archivo }} {{ $d->tipo ? '(' . $d->tipo . ')' : '' }}
        </option>
      @endforeach
    </select>
    @error('pdf_doc_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror

    <p class="text-xs text-gray-500 mt-2 flex items-center gap-2">
      <i class="fa-solid fa-circle-info text-indigo-400"></i>
      Puedes subir el PDF en
      <a href="{{ route('documents.index') }}" class="text-indigo-600 hover:underline font-medium">
        Documentos
      </a>
      y vincularlo aquí.
    </p>
  </div>
</div>
