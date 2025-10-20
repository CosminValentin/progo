@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-10 rounded-2xl bg-gradient-to-br from-indigo-50 via-white to-sky-50 shadow-xl" x-data="{ showErrors:true }">
  <div class="mb-8 border-b pb-5">
    <h1 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-700 to-sky-600 bg-clip-text text-transparent">
      Nuevo participante
    </h1>
    <p class="text-sm text-indigo-500 mt-1">Completa los datos y guarda para dar de alta.</p>
  </div>

  @if ($errors->any())
    <div x-show="showErrors"
         class="mb-6 rounded-xl border border-rose-200/80 bg-rose-50 p-4 text-rose-800 shadow-sm">
      <div class="flex items-start gap-3">
        <svg class="h-5 w-5 mt-0.5 text-rose-600" xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M12 5a7 7 0 100 14 7 7 0 000-14z"/>
        </svg>
        <div class="flex-1">
          <strong class="block">Corrige los errores</strong>
          <ul class="mt-1 text-sm list-disc list-inside">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
          </ul>
        </div>
        <button class="rounded-full px-2 text-rose-900/70 hover:text-rose-900" @click="showErrors=false">✕</button>
      </div>
    </div>
  @endif

  <form method="POST" action="{{ route('saveparticipant') }}"
        class="rounded-2xl border border-gray-200/80 bg-white p-6 shadow-lg space-y-6">
    @csrf

    @include('participants._form')

    <div class="flex items-center justify-end gap-3 pt-2">
      <a href="{{ route('participants') }}"
         class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 shadow-sm hover:shadow transition">
        Cancelar
      </a>
      <button
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold shadow hover:shadow-lg hover:scale-[1.02] active:scale-100 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-90" fill="none" viewBox="0 0 24 24"
             stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
             d="M5 13l4 4L19 7"/></svg>
        Guardar
      </button>
    </div>
  </form>
</div>
@endsection
