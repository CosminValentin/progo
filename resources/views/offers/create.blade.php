@extends('layouts.auth')
@section('title','Nueva oferta')

@section('content')
<div class="max-w-3xl mx-auto p-6">
  <div class="mb-6">
    <h1 class="text-3xl font-bold">Nueva oferta</h1>
    <p class="text-gray-500">Rellena los datos de la oferta laboral.</p>
  </div>

  @if($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('offers.store') }}" class="bg-white rounded-3xl shadow-xl p-6 md:p-8 space-y-6">
    @csrf

    <div class="grid md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Empresa *</label>
        <select name="company_id" required class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-2.5">
          <option value="">Selecciona…</option>
          @foreach($companies as $c)
            <option value="{{ $c->id }}" @selected(old('company_id')==$c->id)>{{ $c->nombre }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
        <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-2.5">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Puesto *</label>
      <input name="puesto" value="{{ old('puesto') }}" required class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-2.5">
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de contrato</label>
        <input name="tipo_contrato" value="{{ old('tipo_contrato') }}" class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-2.5">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jornada (%)</label>
        <input type="number" name="jornada_pct" min="1" max="100" value="{{ old('jornada_pct') }}" class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-2.5">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
      <input name="ubicacion" value="{{ old('ubicacion') }}" class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-2.5">
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Requisitos</label>
      <textarea name="requisitos" rows="3" class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-2.5">{{ old('requisitos') }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
      <select name="estado" class="w-full rounded-2xl border-gray-200 bg-gray-50 px-4 py-2.5">
        <option value="abierta" @selected(old('estado')=='abierta')>Abierta</option>
        <option value="cerrada" @selected(old('estado')=='cerrada')>Cerrada</option>
      </select>
    </div>

    <div class="flex gap-3">
      <button class="px-5 py-2.5 rounded-2xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">Guardar</button>
      <a href="{{ route('offers.index') }}" class="px-5 py-2.5 rounded-2xl bg-gray-100 hover:bg-gray-200">Cancelar</a>
    </div>
  </form>
</div>
@endsection
