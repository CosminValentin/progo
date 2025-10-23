@props(['participant' => null])

@php
  // Helper para obtener valores con prioridad: old input > participante > valor por defecto
  function oldOrModel($field, $participant, $default = '') {
    if (old($field) !== null) {
      return old($field);
    }
    if ($participant && isset($participant->$field)) {
      // Si es fecha y tiene método format, formatear a Y-m-d (ideal para date inputs)
      if ($participant->$field instanceof \DateTimeInterface) {
        return $participant->$field->format('Y-m-d');
      }
      return $participant->$field;
    }
    return $default;
  }
@endphp

<div class="rounded-xl border border-indigo-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-indigo-700 dark:text-indigo-300 mb-5 flex items-center">
    <i class="fa-regular fa-id-badge mr-2"></i> Identificación
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
      <label for="dni_nie" class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">
        DNI/NIE <span class="text-rose-600">*</span>
      </label>
      <input id="dni_nie" name="dni_nie" type="text" maxlength="16" required
             value="{{ oldOrModel('dni_nie', $participant) }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner
                    focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      @error('dni_nie')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="nombre" class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">
        Nombre completo <span class="text-rose-600">*</span>
      </label>
      <input id="nombre" name="nombre" type="text" maxlength="120" required
             value="{{ oldOrModel('nombre', $participant) }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner
                    focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
      @error('nombre')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
      @enderror
    </div>
  </div>
</div>

<div class="mt-6 rounded-xl border border-sky-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-sky-700 dark:text-sky-300 mb-5 flex items-center">
    <i class="fa-regular fa-envelope mr-2"></i> Contacto
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
      <label for="email" class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Email</label>
      <input id="email" name="email" type="email" maxlength="120"
             value="{{ oldOrModel('email', $participant) }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner
                    focus:ring-2 focus:ring-sky-500 focus:outline-none transition">
      @error('email')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="telefono" class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Teléfono</label>
      <input id="telefono" name="telefono" type="tel" maxlength="30"
             value="{{ oldOrModel('telefono', $participant) }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner
                    focus:ring-2 focus:ring-sky-500 focus:outline-none transition">
      @error('telefono')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
      @enderror
    </div>
  </div>
</div>

<div class="mt-6 rounded-xl border border-emerald-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-emerald-700 dark:text-emerald-300 mb-5 flex items-center">
    <i class="fa-regular fa-calendar mr-2"></i> Programa
  </h3>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div>
      <label for="fecha_alta_prog" class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">
        Fecha alta <span class="text-rose-600">*</span>
      </label>
      <input id="fecha_alta_prog" name="fecha_alta_prog" type="date" required
             value="{{ oldOrModel('fecha_alta_prog', $participant) }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner
                    focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
      @error('fecha_alta_prog')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="provincia" class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Provincia</label>
      <input id="provincia" name="provincia" type="text" maxlength="40"
             value="{{ oldOrModel('provincia', $participant) }}"
             class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner
                    focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
      @error('provincia')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="estado" class="block text-xs font-medium text-gray-700 dark:text-slate-300 mb-1">Estado</label>
      @php
        $statusOptions = ['activo' => 'Activo', 'pendiente' => 'Pendiente', 'inactivo' => 'Inactivo'];
        $selectedStatus = old('estado', $participant->estado ?? 'activo');
      @endphp
      <select id="estado" name="estado"
              class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner
                     focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
        @foreach($statusOptions as $value => $label)
          <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
        @endforeach
      </select>
      @error('estado')
        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
      @enderror
    </div>
  </div>
</div>

<div class="mt-6 rounded-xl border border-fuchsia-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
  <h3 class="text-sm font-semibold tracking-wide text-fuchsia-700 dark:text-fuchsia-300 mb-5 flex items-center">
    <i class="fa-regular fa-note-sticky mr-2"></i> Notas
  </h3>
  <textarea id="notas" name="notas" rows="4"
            placeholder="Observaciones, detalles…"
            class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-inner
                   focus:ring-2 focus:ring-fuchsia-500 focus:outline-none transition resize-y">{{ oldOrModel('notas', $participant) }}</textarea>
  @error('notas')
    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
  @enderror
</div>
