@extends('layouts.auth')
@section('title','Editar Job')

@section('content')
<h1 class="text-2xl font-extrabold mb-4">Editar Job</h1>

@if($errors->any())
  <div class="card mb-4 text-rose-700 bg-rose-50 border border-rose-200">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('jobs.update', $job) }}" class="card space-y-6">
  @csrf @method('PUT')

  <div class="grid md:grid-cols-2 gap-6">
    <div>
      <label class="block text-sm font-medium mb-1">Título *</label>
      <input name="title" value="{{ old('title', $job->title) }}" required class="input">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Empresa</label>
      <select name="company_id" class="select">
        <option value="">—</option>
        @foreach($companies as $c)
          <option value="{{ $c->id }}" @selected(old('company_id', $job->company_id)==$c->id)>{{ $c->nombre }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Tipo</label>
      <input name="type" value="{{ old('type', $job->type) }}" class="input">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Ubicación</label>
      <input name="location" value="{{ old('location', $job->location) }}" class="input">
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Salario mín.</label>
      <input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" class="input">
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Salario máx.</label>
      <input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" class="input">
    </div>
  </div>

  <div>
    <label class="block text-sm font-medium mb-1">Descripción</label>
    <textarea name="description" rows="5" class="textarea">{{ old('description', $job->description) }}</textarea>
  </div>

  <div class="grid md:grid-cols-2 gap-6">
    <div>
      <label class="block text-sm font-medium mb-1">Estado *</label>
      <select name="status" class="select" required>
        <option value="open"   @selected(old('status', $job->status)==='open')>Open</option>
        <option value="closed" @selected(old('status', $job->status)==='closed')>Closed</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium mb-1">Fecha publicación</label>
      <input type="date" name="posted_at" value="{{ old('posted_at', optional($job->posted_at)->format('Y-m-d')) }}" class="input">
    </div>
  </div>

  <div class="flex gap-2">
    <button class="btn-primary">Actualizar</button>
    <a href="{{ route('jobs.index') }}" class="btn-soft">Cancelar</a>
  </div>

  <div class="mt-4">
    <form method="POST" action="{{ route('jobs.destroy', $job) }}" onsubmit="return confirm('¿Eliminar este job?')">
      @csrf @method('DELETE')
      <button class="btn-danger">Eliminar</button>
    </form>
  </div>
</form>
@endsection
