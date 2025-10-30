@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold text-indigo-700 dark:text-indigo-400">Editar candidatura #{{ $application->id }}</h1>
    <a href="{{ route('applications.index') }}" class="px-4 py-2 rounded-lg border dark:border-slate-700">Volver</a>
  </div>
@endsection

@section('content')
  <div class="rounded-2xl bg-white dark:bg-slate-800 shadow p-6">
    <form method="POST" action="{{ route('applications.update', $application) }}" class="space-y-4">
      @csrf
      @method('PUT')

      <input type="hidden" name="participant_id" value="{{ $application->participant_id }}">

      <div>
        <label class="block text-sm mb-1">Oferta</label>
        <select name="offer_id" class="w-full border rounded-lg px-3 py-2 dark:bg-slate-900 dark:border-slate-700" required>
          @foreach($offers as $o)
            <option value="{{ $o->id }}" @selected($o->id == $application->offer_id)>
              {{ $o->puesto }} @if($o->company) — {{ $o->company->nombre }} @endif
            </option>
          @endforeach
        </select>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm mb-1">Fecha</label>
          <input type="date" name="fecha" value="{{ optional($application->fecha)->format('Y-m-d') }}"
                 class="w-full border rounded-lg px-3 py-2 dark:bg-slate-900 dark:border-slate-700" required>
        </div>
        <div>
          <label class="block text-sm mb-1">Estado</label>
          <select name="estado" class="w-full border rounded-lg px-3 py-2 dark:bg-slate-900 dark:border-slate-700" required>
            @foreach(['pendiente','en_proceso','aceptada','rechazada'] as $e)
              <option value="{{ $e }}" @selected($application->estado === $e)>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="flex justify-end gap-2 pt-3 border-t dark:border-slate-700">
        <a href="{{ route('applications.index') }}" class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-slate-700">Cancelar</a>
        <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white">Guardar</button>
      </div>
    </form>
  </div>
@endsection
