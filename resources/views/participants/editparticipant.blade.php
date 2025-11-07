@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-2xl font-bold">Editar participante</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">
        Actualiza la información de <strong>{{ $participant->nombre ?? 'Participante' }}</strong>.
      </p>
    </div>
    <a href="{{ route('participants') }}" class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-sm">
      Volver
    </a>
  </div>
@endsection

@section('content')
  <div x-data="{ open: false }">
    @if ($errors->any())
      <div class="mb-4 rounded-lg border border-rose-200/80 bg-rose-50 p-3 text-rose-800 shadow-sm dark:border-rose-700/40 dark:bg-rose-900/30 dark:text-rose-200 text-sm">
        <strong class="block mb-1">Corrige los errores</strong>
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('updateparticipant', $participant) }}"
          class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 md:p-6 shadow space-y-4">
      @csrf
      @method('PUT')

      @include('participants._form', ['participant' => $participant])

      <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 sm:gap-0 pt-1">
        <button type="button" @click="open = true"
                class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 dark:border-rose-800 dark:text-rose-200 dark:bg-rose-900/30 dark:hover:bg-rose-900/40 text-sm w-full sm:w-auto text-center">
          Eliminar
        </button>
        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow text-sm w-full sm:w-auto">
          Guardar cambios
        </button>
      </div>
    </form>

    <!-- Modal eliminar participante -->
    <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <!-- Fondo semi-transparente -->
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>

      <!-- Contenedor principal -->
      <div x-transition
          class="relative w-full max-w-md rounded-3xl bg-gradient-to-br from-rose-50 to-rose-100 dark:from-rose-900/40 dark:to-rose-800/40 shadow-2xl ring-1 ring-black/10 dark:ring-white/20 transform transition-all duration-300">
          
        <!-- Encabezado -->
        <div class="px-6 py-5 border-b border-rose-200 dark:border-rose-700 text-center">
          <h3 class="text-xl font-bold text-rose-700 dark:text-rose-200 flex items-center justify-center gap-2">
            <i class="fa-solid fa-exclamation-triangle text-2xl"></i>
            Eliminar participante
          </h3>
          <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">
            ¿Eliminar <strong>{{ $participant->nombre ?? '' }}</strong> ({{ $participant->dni_nie ?? '' }})?
          </p>
        </div>

        <!-- Botones -->
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:justify-between gap-3">
          <button @click="open = false" type="button"
                  class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700/60 transition shadow-sm hover:shadow-md">
            Cancelar
          </button>

          <form method="POST" action="{{ route('deleteparticipant', $participant) }}" class="w-full sm:w-auto">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-red-600 text-white font-semibold hover:from-rose-700 hover:to-red-700 shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
              Sí, eliminar
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>
@endsection
