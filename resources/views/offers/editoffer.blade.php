@extends('layouts.app_windmill')

@section('header')
  <div class="flex items-end justify-between">
    <div>
      <h1 class="text-2xl font-bold">Editar oferta</h1>
      <p class="text-sm text-gray-500 dark:text-slate-400">
        Actualiza la información de <strong>{{ $offer->display_title }}</strong>.
      </p>
    </div>
<a href="{{ route('offers') }}"
       class="px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700">
      Volver
    </a>
  </div>
@endsection

@section('content')
  <div x-data="{open:false}">
    @if ($errors->any())
      <div class="mb-6 rounded-xl border border-rose-200/80 bg-rose-50 p-4 text-rose-800 shadow-sm dark:border-rose-700/40 dark:bg-rose-900/30 dark:text-rose-200">
        <strong class="block mb-1">Corrige los errores</strong>
        <ul class="text-sm list-disc list-inside">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('offers.update', $offer) }}"
          class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow space-y-6">
      @csrf
      @method('PUT')

      @include('offers._form', ['offer' => $offer, 'companies' => $companies])

      <div class="flex items-center justify-between pt-2">
        <button type="button" @click="open=true"
                class="px-4 py-2 rounded-lg border border-rose-200 text-rose-700 bg-rose-50 hover:bg-rose-100 dark:border-rose-800 dark:text-rose-200 dark:bg-rose-900/30 dark:hover:bg-rose-900/40">
          Eliminar
        </button>
        <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow">Actualizar</button>
      </div>
    </form>

    <!-- Modal eliminar -->
    <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/40" @click="open=false"></div>
      <div x-transition class="relative w-full max-w-md rounded-2xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-black/5 dark:ring-white/10">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-slate-700">
          <h3 class="text-lg font-semibold text-center">Eliminar oferta</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-slate-300 text-center">
            ¿Eliminar <strong>{{ $offer->display_title }}</strong>?
          </p>
        </div>
        <div class="px-6 py-5 flex flex-col sm:flex-row sm:justify-between gap-3">
          <button @click="open=false"
                  class="px-4 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700/60">
            Cancelar
          </button>
          <form method="POST" action="{{ route('offers.destroy', $offer) }}">
            @csrf
            @method('DELETE')
            <button class="px-4 py-2.5 rounded-lg bg-gradient-to-r from-rose-600 to-red-600 text-white hover:shadow dark:from-rose-500 dark:to-red-500">
              Sí, eliminar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
