@props(['participant' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block text-sm font-medium mb-1">DNI/NIE <span class="text-red-600">*</span></label>
    <input name="dni_nie" value="{{ old('dni_nie', $participant->dni_nie ?? '') }}"
           class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500" required maxlength="16">
    @error('dni_nie')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Nombre <span class="text-red-600">*</span></label>
    <input name="nombre" value="{{ old('nombre', $participant->nombre ?? '') }}"
           class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500" required maxlength="120">
    @error('nombre')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Email</label>
    <input type="email" name="email" value="{{ old('email', $participant->email ?? '') }}"
           class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500" maxlength="120">
    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Teléfono</label>
    <input name="telefono" value="{{ old('telefono', $participant->telefono ?? '') }}"
           class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500" maxlength="30">
    @error('telefono')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Fecha alta programa <span class="text-red-600">*</span></label>
    <input type="date" name="fecha_alta_prog"
           value="{{ old('fecha_alta_prog', optional($participant->fecha_alta_prog ?? null)->format('Y-m-d')) }}"
           class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
    @error('fecha_alta_prog')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Provincia</label>
    <input name="provincia" value="{{ old('provincia', $participant->provincia ?? '') }}"
           class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500" maxlength="40">
    @error('provincia')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm font-medium mb-1">Notas</label>
    <textarea name="notas" rows="4"
              class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
              placeholder="Observaciones, detalles…">{{ old('notas', $participant->notas ?? '') }}</textarea>
    @error('notas')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>
</div>
