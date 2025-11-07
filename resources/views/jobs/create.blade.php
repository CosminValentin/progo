@extends('layouts.auth')
@section('title','Nuevo Job')

@section('content')
<h1 class="text-2xl font-extrabold mb-4">Nuevo Job</h1>

@if($errors->any())
  <div class="card mb-4 text-rose-700 bg-rose-50 border border-rose-200">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('jobs.store') }}" class="card space-y-6">
  @csrf
  <div class="grid md:grid-cols-2 gap-6">
    <div>
      <label class="block text-sm font-medium mb-1">Título *</label>
      <input name="title" value="{{ old('title') }}" required class="input">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Empresa</label>
      <select name="company_id" class="select">
        <option value="">—</option>
        @foreach($companies as $c)
          <option value="{{ $c->id }}" @selected(old('company_id')==$c->id)>{{ $c->nombre }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Tipo</label>
      <input name="type" value="{{ old('type') }}" placeholder="Full-time, Part-time…" class="input">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Ubicación</label>
      <input name="location" value="{{ old('location') }}" class="input">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Salario mín.</label>
      <input type="number" name="salary_min" value="{{ old('salary_min') }}" class="input">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Salario máx.</label>
      <input type="number" name="salary_max" value="{{ old('salary_max') }}" class="input">
    </div>
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Descripción</label>
    <textarea name="description" rows="5" class="textarea">{{ old('description') }}</textarea>
  </div>

  <div class="grid md:grid-cols-2 gap-6">
    <div>
      <label class="block text-sm font-medium mb-1">Estado *</label>
      <select name="status" class="select" required>
        <option value="open"   @selected(old('status')==='open')>Open</option>
        <option value="closed" @selected(old('status')==='closed')>Closed</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Fecha publicación</label>
      <input type="date" name="posted_at" value="{{ old('posted_at', date('Y-m-d')) }}" class="input">
    </div>
  </div>

  <div class="flex gap-2">
    <button class="btn-primary">Guardar</button>
    <a href="{{ route('jobs.index') }}" class="btn-soft">Cancelar</a>
  </div>
</form>
@endsection
