@extends('layouts.app_windmill')

@section('header')
  <h1 class="text-2xl font-bold">{{ $company->nombre }}</h1>
@endsection

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow">
      <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200 mb-3">Identificación</h2>
      <dl class="text-sm space-y-2">
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">CIF/NIF</dt><dd class="font-medium">{{ $company->cif_nif }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Nombre</dt><dd class="font-medium">{{ $company->nombre }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Ámbito</dt><dd class="font-medium">{{ $company->ambito ?: '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Actividad</dt><dd class="font-medium">{{ $company->actividad ?: '—' }}</dd></div>
      </dl>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow">
      <h2 class="text-sm font-semibold text-gray-700 dark:text-slate-200 mb-3">Contacto</h2>
      <dl class="text-sm space-y-2">
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Nombre</dt><dd class="font-medium">{{ $company->contacto_nombre ?: '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Email</dt><dd class="font-medium">{{ $company->contacto_email ?: '—' }}</dd></div>
        <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Teléfono</dt><dd class="font-medium">{{ $company->contacto_tel ?: '—' }}</dd></div>
      </dl>
    </div>
  </div>

  <div class="mt-6 flex gap-2">
    <a href="{{ route('editcompany', $company) }}" class="px-4 py-2 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 hover:shadow transition">Editar</a>
    <a href="{{ route('companies') }}" class="px-4 py-2 rounded-lg border hover:bg-gray-50 dark:hover:bg-slate-700 transition">Volver al listado</a>
  </div>
@endsection
