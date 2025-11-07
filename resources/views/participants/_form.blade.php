@props(['participant' => null])

@php
  function oldOrModel($field, $participant, $default = '') {
      if (old($field) !== null) return old($field);
      if ($participant && isset($participant->$field)) {
          if ($participant->$field instanceof \DateTimeInterface) return $participant->$field->format('Y-m-d');
          return $participant->$field;
      }
      return $default;
  }
@endphp

@php
  $sections = [
    'identificacion' => [
        'title' => 'Identificación',
        'icon' => 'fa-id-badge',
        'bgLight' => 'bg-gradient-to-r from-indigo-100 to-indigo-50',
        'textLight' => 'text-indigo-700',
        'bgDark' => 'dark:bg-gradient-to-r dark:from-indigo-800/30 dark:to-indigo-900/30',
        'textDark' => 'dark:text-indigo-300',
    ],
    'contacto' => [
        'title' => 'Contacto',
        'icon' => 'fa-envelope',
        'bgLight' => 'bg-gradient-to-r from-sky-100 to-sky-50',
        'textLight' => 'text-sky-700',
        'bgDark' => 'dark:bg-gradient-to-r dark:from-sky-800/30 dark:to-sky-900/30',
        'textDark' => 'dark:text-sky-300',
    ],
    'programa' => [
        'title' => 'Programa',
        'icon' => 'fa-calendar',
        'bgLight' => 'bg-gradient-to-r from-emerald-100 to-emerald-50',
        'textLight' => 'text-emerald-700',
        'bgDark' => 'dark:bg-gradient-to-r dark:from-emerald-800/30 dark:to-emerald-900/30',
        'textDark' => 'dark:text-emerald-300',
    ],
    'notas' => [
        'title' => 'Notas',
        'icon' => 'fa-note-sticky',
        'bgLight' => 'bg-gradient-to-r from-fuchsia-100 to-fuchsia-50',
        'textLight' => 'text-fuchsia-700',
        'bgDark' => 'dark:bg-gradient-to-r dark:from-fuchsia-800/30 dark:to-fuchsia-900/30',
        'textDark' => 'dark:text-fuchsia-300',
    ],
  ];
@endphp

@foreach($sections as $key => $section)
  <div class="mt-4 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-md p-4 md:p-5 transition-all hover:shadow-xl {{ $section['bgLight'] }} {{ $section['bgDark'] }}">
    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 {{ $section['textLight'] }} {{ $section['textDark'] }}">
      <i class="fa-regular {{ $section['icon'] }} text-xl"></i> {{ $section['title'] }}
    </h3>

    @if($key === 'identificacion')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="dni_nie" class="block text-sm font-medium mb-1">DNI/NIE <span class="text-rose-600">*</span></label>
          <input id="dni_nie" name="dni_nie" type="text" maxlength="16" required
                 value="{{ oldOrModel('dni_nie', $participant) }}"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition text-sm">
          @error('dni_nie') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="nombre" class="block text-sm font-medium mb-1">Nombre completo <span class="text-rose-600">*</span></label>
          <input id="nombre" name="nombre" type="text" maxlength="120" required
                 value="{{ oldOrModel('nombre', $participant) }}"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition text-sm">
          @error('nombre') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
      </div>
    @elseif($key === 'contacto')
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="email" class="block text-sm font-medium mb-1">Email</label>
          <input id="email" name="email" type="email" maxlength="120"
                 value="{{ oldOrModel('email', $participant) }}"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition text-sm">
          @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="telefono" class="block text-sm font-medium mb-1">Teléfono</label>
          <input id="telefono" name="telefono" type="tel" maxlength="30"
                 value="{{ oldOrModel('telefono', $participant) }}"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-sm focus:ring-2 focus:ring-sky-500 focus:outline-none transition text-sm">
          @error('telefono') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
      </div>
    @elseif($key === 'programa')
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label for="fecha_alta_prog" class="block text-sm font-medium mb-1">Fecha alta <span class="text-rose-600">*</span></label>
          <input id="fecha_alta_prog" name="fecha_alta_prog" type="date" required
                 value="{{ oldOrModel('fecha_alta_prog', $participant) }}"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition text-sm">
          @error('fecha_alta_prog') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="provincia" class="block text-sm font-medium mb-1">Provincia</label>
          <input id="provincia" name="provincia" type="text" maxlength="40"
                 value="{{ oldOrModel('provincia', $participant) }}"
                 class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition text-sm">
          @error('provincia') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
        <div>
          @php
            $statusOptions = ['activo' => 'Activo', 'pendiente' => 'Pendiente', 'inactivo' => 'Inactivo'];
            $selectedStatus = old('estado', $participant->estado ?? 'activo');
          @endphp
          <label for="estado" class="block text-sm font-medium mb-1">Estado</label>
          <select id="estado" name="estado"
                  class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition text-sm">
            @foreach($statusOptions as $value => $label)
              <option value="{{ $value }}" @selected($selectedStatus === $value)>{{ $label }}</option>
            @endforeach
          </select>
          @error('estado') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
        </div>
      </div>
    @elseif($key === 'notas')
      <div class="mb-4">
        <label for="notas" class="block text-sm font-medium mb-1">Observaciones (generales)</label>
        <textarea id="notas" name="notas" rows="3"
                  class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-sm focus:ring-2 focus:ring-fuchsia-500 focus:outline-none transition resize-y text-sm"
                  placeholder="Observaciones, detalles…">{{ oldOrModel('notas', $participant) }}</textarea>
        @error('notas') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
      </div>
      <div>
        <label for="observaciones2" class="block text-sm font-medium mb-1">Observaciones del trabajador (internas)</label>
        <textarea id="observaciones2" name="observaciones2" rows="3"
                  class="w-full rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2.5 shadow-sm focus:ring-2 focus:ring-fuchsia-500 focus:outline-none transition resize-y text-sm"
                  placeholder="Información interna del trabajador, seguimiento, acuerdos…">{{ oldOrModel('observaciones2', $participant) }}</textarea>
        @error('observaciones2') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
      </div>
    @endif
  </div>
@endforeach
