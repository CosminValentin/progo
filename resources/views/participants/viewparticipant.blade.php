@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10 bg-gradient-to-br from-indigo-50 via-white to-cyan-50 rounded-2xl shadow-lg">
  <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b pb-4">
    <div>
      <h1 class="text-4xl font-extrabold text-indigo-700 tracking-tight">{{ $participant->nombre }}</h1>
      <p class="text-sm text-indigo-500 mt-1">🆔 DNI/NIE: <span class="font-semibold">{{ $participant->dni_nie }}</span></p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('editparticipant', $participant) }}"
         class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-indigo-500 to-blue-500 text-white shadow hover:scale-105 transform transition-all">
        ✏️ Editar
      </a>
      <form action="{{ route('deleteparticipant', $participant) }}" method="POST"
            x-data @submit.prevent="if(confirm('¿Eliminar a {{ $participant->nombre }}?')) $el.submit()">
        @csrf
        <button
          class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-rose-500 to-red-500 text-white shadow hover:scale-105 transform transition-all">
          🗑️ Eliminar
        </button>
      </form>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="rounded-xl bg-white border border-indigo-100 p-6 shadow-md hover:shadow-lg transition-all">
      <h2 class="text-lg font-semibold text-indigo-700 mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m0 0H5m3 0h13" />
        </svg>
        Datos de contacto
      </h2>
      <dl class="space-y-4 text-sm">
        <div class="flex justify-between items-center">
          <dt class="text-gray-500">📧 Email</dt>
          <dd class="font-medium text-gray-800">{{ $participant->email ?: '—' }}</dd>
        </div>
        <div class="flex justify-between items-center">
          <dt class="text-gray-500">📞 Teléfono</dt>
          <dd class="font-medium text-gray-800">{{ $participant->telefono ?: '—' }}</dd>
        </div>
        <div class="flex justify-between items-center">
          <dt class="text-gray-500">📍 Provincia</dt>
          <dd class="font-medium text-gray-800">{{ $participant->provincia ?: '—' }}</dd>
        </div>
      </dl>
    </div>

    <div class="rounded-xl bg-white border border-green-100 p-6 shadow-md hover:shadow-lg transition-all">
      <h2 class="text-lg font-semibold text-green-700 mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4v16m8-8H4" />
        </svg>
        Situación
      </h2>
      <dl class="space-y-4 text-sm">
        <div class="flex justify-between items-center">
          <dt class="text-gray-500">📅 Fecha alta programa</dt>
          <dd class="font-medium text-gray-800">
            {{ optional($participant->fecha_alta_prog)->format('d/m/Y') ?: '—' }}
          </dd>
        </div>
        <div class="flex justify-between items-center">
          <dt class="text-gray-500">🔖 Estado</dt>
          <dd>
            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold shadow
                         {{ $participant->estado === 'activo'
                            ? 'bg-green-100 text-green-700 border border-green-200'
                            : 'bg-gray-100 text-gray-600 border border-gray-200' }}">
              {{ ucfirst($participant->estado ?? '—') }}
            </span>
          </dd>
        </div>
      </dl>
    </div>

    <div class="md:col-span-2 rounded-xl bg-white border border-purple-100 p-6 shadow-md hover:shadow-lg transition-all">
      <h2 class="text-lg font-semibold text-purple-700 mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-3-3v6m9-9a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Notas
      </h2>
      <div class="prose max-w-none text-sm text-gray-800 bg-purple-50/30 rounded-lg p-4 border border-purple-100">
        {!! nl2br(e($participant->notas ?? '—')) !!}
      </div>
    </div>
  </div>

  <div class="mt-8 text-center">
    <a href="{{ route('participants') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-gradient-to-r from-sky-500 to-cyan-500 text-white font-semibold shadow hover:scale-105 transform transition-all">
      ← Volver al listado
    </a>
  </div>
</div>
@endsection
