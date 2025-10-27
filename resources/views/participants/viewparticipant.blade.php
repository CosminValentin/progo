@extends('layouts.app_windmill')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-300 tracking-tight">{{ $participant->nombre }}</h1>
        <p class="text-sm text-gray-500 dark:text-slate-400">DNI/NIE: {{ $participant->dni_nie }}</p>
    </div>
    <div class="flex gap-4 mt-4 sm:mt-0">
        <a href="{{ route('editparticipant', $participant) }}" 
           class="px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-xl 
                  hover:from-indigo-700 hover:to-indigo-800 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
            Editar
        </a>
        <a href="{{ route('participants') }}" 
           class="px-6 py-3 rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 
                  text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700 shadow-md 
                  transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
            Volver
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">

    <!-- Datos de contacto -->
    <div class="rounded-3xl p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800 bg-gradient-to-br from-indigo-50 to-indigo-100
                transform transition-all duration-500 hover:scale-105 hover:shadow-2xl relative overflow-hidden animate-fadeInUp">
        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-400 via-indigo-300 to-indigo-400 opacity-10 blur-xl animate-gradientX"></div>
        <h2 class="text-xl font-semibold mb-4 text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
            <i class="fas fa-address-card text-indigo-600 dark:text-indigo-300 animate-bounce"></i>
            Datos de contacto
        </h2>
        <dl class="space-y-4 text-sm relative z-10">
            <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Email</dt><dd class="font-medium text-gray-700 dark:text-slate-300">{{ $participant->email ?: '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Teléfono</dt><dd class="font-medium text-gray-700 dark:text-slate-300">{{ $participant->telefono ?: '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Provincia</dt><dd class="font-medium text-gray-700 dark:text-slate-300">{{ $participant->provincia ?: '—' }}</dd></div>
        </dl>
    </div>

    <!-- Situación -->
    <div class="rounded-3xl p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800 bg-green-50
                transform transition-all duration-500 hover:scale-105 hover:shadow-2xl">
        <h2 class="text-xl font-semibold mb-4 text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
            <i class="fas fa-clipboard-list text-green-600 dark:text-green-300"></i>
            Situación
        </h2>
        <dl class="space-y-4 text-sm">
            <div class="flex justify-between">
                <dt class="text-gray-500 dark:text-slate-400">Fecha alta programa</dt>
                <dd class="font-medium text-gray-700 dark:text-slate-300">{{ optional($participant->fecha_alta_prog)->format('d/m/Y') ?: '—' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-gray-500 dark:text-slate-400">Estado</dt>
                <dd>
                    <span class="inline-flex items-center rounded-full px-4 py-1 text-xs
                                 {{ $participant->estado === 'activo'
                                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200'
                                    : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-200' }}">
                        {{ ucfirst($participant->estado ?? '—') }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>

    <!-- Notas -->
    <div class="md:col-span-2 lg:col-span-1 rounded-3xl p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800 bg-yellow-50
                transform transition-all duration-500 hover:scale-105 hover:shadow-2xl">
        <h2 class="text-xl font-semibold mb-4 text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
            <i class="fas fa-sticky-note text-yellow-600 dark:text-yellow-300"></i>
            Notas
        </h2>
        <div class="prose max-w-none text-sm dark:prose-invert">
            {!! nl2br(e($participant->notas ?? '—')) !!}
        </div>
    </div>

    @if($participant->observaciones2)
    <div class="md:col-span-3 mt-3 rounded-2xl border p-4 bg-gray-50 dark:bg-slate-800 border-gray-200 dark:border-slate-700 shadow-md
                transform transition-all duration-500 hover:scale-105 hover:shadow-lg">
        <span class="font-semibold text-gray-700 dark:text-slate-300">Observaciones (trabajador):</span>
        <p class="mt-1 text-sm text-gray-600 dark:text-slate-300 whitespace-pre-line">{{ $participant->observaciones2 }}</p>
    </div>
    @endif
</div>

<style>
/* Animación de aparición solo para Datos de contacto */
@keyframes fadeInUp {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}
.animate-fadeInUp { animation: fadeInUp 0.6s ease forwards; }

/* Gradiente animado solo para Datos de contacto */
@keyframes gradientX {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}
.animate-gradientX {
  background-size: 200% 200%;
  animation: gradientX 5s ease infinite;
}
</style>
@endsection
