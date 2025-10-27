@extends('layouts.app_windmill')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-indigo-700 dark:text-indigo-400">Timeline — {{ $participant->nombre }}</h1>
        <p class="text-sm text-gray-600 dark:text-slate-400">Histórico de actividad</p>
    </div>
    <a href="{{ route('viewparticipant', $participant) }}"
       class="px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 shadow-sm transition">
       Volver a ficha
    </a>
</div>
@endsection

@section('content')
<div class="relative pl-12">
    <!-- Línea central del timeline -->
    <div class="absolute left-6 top-0 bottom-0 w-px bg-gray-300 dark:bg-slate-600"></div>

    @forelse($events as $index => $e)
    @php
        // Definir color según tipo de evento
        $color = match($e['type'] ?? 'default') {
            'success' => 'from-green-400 to-green-600',
            'warning' => 'from-yellow-400 to-yellow-600',
            'error' => 'from-red-400 to-red-600',
            'info' => 'from-blue-400 to-blue-600',
            default => 'from-indigo-400 to-indigo-600',
        };

        // Alternar lado izquierdo/derecho
        $isLeft = $index % 2 === 0;
    @endphp

    <div class="relative mb-10 flex flex-col sm:flex-row sm:items-start 
                @if(!$isLeft) sm:flex-row-reverse @endif animate-slideUpFade">
        <!-- Icono del evento -->
        <div class="absolute -left-9 sm:-left-9 mt-1 h-10 w-10 rounded-full flex items-center justify-center
                    bg-gradient-to-br {{ $color }} text-white shadow-lg ring-2 ring-white dark:ring-slate-800 
                    transform transition hover:scale-110 hover:shadow-xl">
            <i class="fa-solid {{ $e['icon'] ?? 'fa-circle' }}"></i>
        </div>

        <!-- Card del evento -->
        <div class="ml-6 sm:ml-0 sm:ml-6 sm:flex-1 rounded-xl border border-gray-200 dark:border-slate-700 
                    bg-white dark:bg-slate-800 p-5 shadow-md hover:shadow-2xl transition duration-300
                    hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 dark:hover:from-slate-800 dark:hover:to-slate-900">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <div class="font-semibold text-gray-800 dark:text-gray-200 text-lg flex items-center gap-3">
                    <span class="bg-gradient-to-br {{ $color }} p-2 rounded-full text-white shadow-lg transform transition hover:scale-110">
                        <i class="fa-solid {{ $e['icon'] ?? 'fa-circle' }}"></i>
                    </span>
                    {{ $e['title'] }}
                </div>
                <div class="mt-2 sm:mt-0 text-xs text-gray-500 dark:text-slate-400 bg-gray-100 dark:bg-slate-900/50 px-3 py-1 rounded-full font-mono">
                    {{ optional($e['date'])->format('d/m/Y H:i') }}
                </div>
            </div>

            @if(!empty($e['desc']))
            <p class="mt-3 text-sm text-gray-700 dark:text-slate-300">{{ $e['desc'] }}</p>
            @endif

            @if(!empty($e['meta']) && is_array($e['meta']))
            <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm">
                @foreach($e['meta'] as $k => $v)
                <div class="flex gap-2">
                    <dt class="w-32 text-gray-500 dark:text-slate-400 font-medium">{{ $k }}:</dt>
                    <dd class="flex-1 text-gray-800 dark:text-slate-200 truncate">{{ $v }}</dd>
                </div>
                @endforeach
            </dl>
            @endif
        </div>
    </div>
    @empty
    <p class="text-gray-600 dark:text-slate-400">No hay eventos.</p>
    @endforelse
</div>

<!-- Animaciones CSS -->
<style>
@keyframes slideUpFade {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}
.animate-slideUpFade {
    animation: slideUpFade 0.6s ease forwards;
}
</style>
@endsection
