@props(['contract'=>null, 'participants'=>collect(), 'companies'=>collect(), 'offers'=>collect(), 'documents'=>collect()])

@php $c = $contract; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

  <div>
    <label class="block text-sm font-medium mb-1">Participante <span class="text-rose-600">*</span></label>
    <select name="participant_id" required
            class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      <option value="">— Selecciona participante —</option>
      @foreach($participants as $p)
        <option value="{{ $p->id }}" @selected(old('participant_id', $c->participant_id ?? '') == $p->id)>{{ $p->nombre }}</option>
      @endforeach
    </select>
    @error('participant_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Empresa <span class="text-rose-600">*</span></label>
    <select name="company_id" required
            class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      <option value="">— Selecciona empresa —</option>
      @foreach($companies as $co)
        <option value="{{ $co->id }}" @selected(old('company_id', $c->company_id ?? '') == $co->id)>{{ $co->nombre }}</option>
      @endforeach
    </select>
    @error('company_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Oferta (opcional)</label>
    <select name="offer_id"
            class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      <option value="">— Sin oferta —</option>
      @foreach($offers as $o)
        <option value="{{ $o->id }}" @selected(old('offer_id', $c->offer_id ?? '') == $o->id)">#{{ $o->id }} — {{ $o->puesto }}</option>
      @endforeach
    </select>
    @error('offer_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Tipo de contrato <span class="text-rose-600">*</span></label>
    <input type="text" name="tipo" required maxlength="60"
           value="{{ old('tipo', $c->tipo ?? '') }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
           placeholder="Indefinido, Temporal, Prácticas…">
    @error('tipo') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Jornada</label>
    <input type="text" name="jornada" maxlength="40"
           value="{{ old('jornada', $c->jornada ?? '') }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
           placeholder="Completa, Parcial…">
    @error('jornada') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Horas/semana</label>
    <input type="number" name="horas_semana" min="1" max="60"
           value="{{ old('horas_semana', $c->horas_semana ?? '') }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
    @error('horas_semana') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Salario (bruto)</label>
    <input type="number" step="0.01" name="salario" min="0"
           value="{{ old('salario', $c->salario ?? '') }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
           placeholder="Ej. 18000.00">
    @error('salario') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Estado</label>
    @php
      $estados = ['activo'=>'Activo','finalizado'=>'Finalizado','baja'=>'Baja'];
      $estadoSel = old('estado', $c->estado ?? 'activo');
    @endphp
    <select name="estado"
            class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      @foreach($estados as $val=>$label)
        <option value="{{ $val }}" @selected($estadoSel === $val)>{{ $label }}</option>
      @endforeach
    </select>
    @error('estado') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Fecha inicio <span class="text-rose-600">*</span></label>
    <input type="date" name="fecha_inicio" required
           value="{{ old('fecha_inicio', optional($c->fecha_inicio ?? null)->format('Y-m-d')) }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
    @error('fecha_inicio') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Fecha fin</label>
    <input type="date" name="fecha_fin"
           value="{{ old('fecha_fin', optional($c->fecha_fin ?? null)->format('Y-m-d')) }}"
           class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
    @error('fecha_fin') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">PDF del contrato (Documento)</label>
    <select name="pdf_doc_id"
            class="w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2.5 shadow-inner focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      <option value="">— Sin documento —</option>
      @foreach($documents as $d)
        <option value="{{ $d->id }}" @selected(old('pdf_doc_id', $c->pdf_doc_id ?? '') == $d->id)">
          #{{ $d->id }} — {{ $d->nombre_archivo }} {{ $d->tipo ? '(' . $d->tipo . ')' : '' }}
        </option>
      @endforeach
    </select>
    @error('pdf_doc_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
    <p class="text-xs text-gray-500 mt-1">
      Puedes subir el PDF en <a href="{{ route('documents.index') }}" class="text-indigo-600 hover:underline">Documentos</a> y vincularlo aquí.
    </p>
  </div>

</div>
