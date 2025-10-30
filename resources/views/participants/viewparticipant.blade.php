{{-- resources/views/participants/viewparticipant.blade.php --}}
@extends('layouts.app_windmill')

@php
  use Illuminate\Support\Str;

  // ====== CARGAS ======
  $companies     = \App\Models\Company::orderBy('nombre')->get();
  $agreements    = \App\Models\Agreement::with('company')->orderByDesc('fecha_firma')->get();

  // Documentos (PDF u otros)
  $documentsPdf  = \App\Models\Document::orderByDesc('fecha')->get();

  // CVs del participante
  $cvDocs = \App\Models\Document::where('tipo','cv')
            ->where('owner_type','participants')
            ->where('owner_id', $participant->id)
            ->orderByDesc('fecha')
            ->get();

  // Ofertas (todas)
  $offers = \App\Models\Offer::with('company')->orderByDesc('fecha')->orderByDesc('id')->get();

  // Contratos del participante
  $contracts = \App\Models\Contract::with(['company','offer'])
      ->where('participant_id', $participant->id)
      ->orderByDesc('fecha_inicio')
      ->orderByDesc('id')
      ->get();

  // Candidaturas del participante
  $applications = \App\Models\Application::with(['offer.company'])
      ->where('participant_id', $participant->id)
      ->orderByDesc('fecha')
      ->orderByDesc('id')
      ->get();

  // Estados de candidatura
  $appEstados = ['pendiente','en_proceso','aceptada','rechazada'];

  // Seguridad Social del participante (si la usas en otra sección)
  $ssRecords = \App\Models\SSRecord::where('participant_id', $participant->id)
      ->orderByDesc('id')
      ->get();

  // ====== SEGUIMIENTO LABORAL (Insertion Checks) ======
  $insertionChecks = \App\Models\InsertionCheck::where('participant_id', $participant->id)
      ->orderByDesc('fecha')
      ->orderByDesc('id')
      ->get();

  // Siguiente seguimiento (el más reciente registrado) para la alerta
  $nextIc = $nextIc
    ?? \App\Models\InsertionCheck::where('participant_id', $participant->id)
        ->orderByDesc('fecha')
        ->orderByDesc('id')
        ->first();
@endphp


@section('header')
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
  <div>
    <h1 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-300 tracking-tight">{{ $participant->nombre }}</h1>
    <p class="text-sm text-gray-500 dark:text-slate-400">DNI/NIE: {{ $participant->dni_nie }}</p>
  </div>
  <div class="flex gap-4 mt-4 sm:mt-0">
    <a href="{{ route('editparticipant', $participant) }}"
       class="px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 text-white shadow-xl hover:from-indigo-700 hover:to-indigo-800 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
      Editar
    </a>
    <a href="{{ route('participants') }}"
       class="px-6 py-3 rounded-xl border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700 shadow-md transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
      Volver
    </a>
  </div>
</div>

@if($nextIc)
  <div id="ic-next"
       data-fecha="{{ optional($nextIc->fecha)->format('Y-m-d') }}"
       data-dias="{{ (int)($nextIc->dias_validos ?? 0) }}"
       class="hidden"></div>
@endif

@section('content')

@isset($nextIc)
  <div id="ic-next"
       data-fecha="{{ $nextIc?->fecha?->format('Y-m-d') }}"
       data-dias="{{ $nextIc->dias_validos ?? 0 }}"
       class="hidden"></div>
@endisset

@if(session('success'))
  <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 p-3 text-emerald-800 dark:border-emerald-700/40 dark:bg-emerald-900/20 dark:text-emerald-200">
    {{ session('success') }}
  </div>
@endif
@if ($errors->any())
  <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 p-3 text-rose-800 dark:border-rose-700/40 dark:bg-rose-900/20 dark:text-rose-200">
    <ul class="list-disc pl-5">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

{{-- ======= Tarjetas de info participante ======= --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">

  {{-- Datos de contacto --}}
  <div class="rounded-3xl p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800 bg-gradient-to-br from-indigo-50 to-indigo-100">
    <h2 class="text-xl font-semibold mb-4 text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fas fa-address-card text-indigo-600 dark:text-indigo-300"></i>
      Datos de contacto
    </h2>
    <dl class="space-y-4 text-sm">
      <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Email</dt><dd class="font-medium text-gray-700 dark:text-slate-300">{{ $participant->email ?: '—' }}</dd></div>
      <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Teléfono</dt><dd class="font-medium text-gray-700 dark:text-slate-300">{{ $participant->telefono ?: '—' }}</dd></div>
      <div class="flex justify-between"><dt class="text-gray-500 dark:text-slate-400">Provincia</dt><dd class="font-medium text-gray-700 dark:text-slate-300">{{ $participant->provincia ?: '—' }}</dd></div>
    </dl>
  </div>

  {{-- Situación --}}
  <div class="rounded-3xl p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800 bg-green-50">
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

  {{-- Notas generales --}}
  <div class="md:col-span-2 lg:col-span-1 rounded-3xl p-6 shadow-xl dark:border-slate-700 dark:bg-slate-800 bg-yellow-50">
    <h2 class="text-xl font-semibold mb-4 text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fas fa-sticky-note text-yellow-600 dark:text-yellow-300"></i>
      Notas
    </h2>
    <div class="prose max-w-none text-sm dark:prose-invert">
      {!! nl2br(e($participant->notas ?? '—')) !!}
    </div>
  </div>

  @if($participant->observaciones2)
  <div class="md:col-span-3 mt-3 rounded-2xl border p-4 bg-gray-50 dark:bg-slate-800 border-gray-200 dark:border-slate-700 shadow-md">
    <span class="font-semibold text-gray-700 dark:text-slate-300">Observaciones (trabajador):</span>
    <p class="mt-1 text-sm text-gray-600 dark:text-slate-300 whitespace-pre-line">{{ $participant->observaciones2 }}</p>
  </div>
  @endif
</div>

{{-- ==================== NOTAS DEL TRABAJADOR ==================== --}}
<div class="mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fa-solid fa-user-pen text-indigo-500"></i>
      Notas del trabajador
    </h2>
    <button
      @click="window.dispatchEvent(new CustomEvent('open-crear-nota'))"
      class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transform transition hover:scale-105">
      <i class="fa-solid fa-plus mr-1"></i> Añadir Nota
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">Fecha</th>
          <th class="px-5 py-3 text-left">Usuario</th>
          <th class="px-5 py-3 text-left">Estado</th>
          <th class="px-5 py-3 text-left">Texto</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse(($participant->notasTrabajador ?? collect()) as $n)
          <tr>
            <td class="px-5 py-3 whitespace-nowrap">{{ optional($n->fecha_hora)->format('d/m/Y H:i') }}</td>
            <td class="px-5 py-3">{{ $n->usuario->name ?? '—' }}</td>
            <td class="px-5 py-3">
              <span class="inline-flex items-center rounded-full px-3 py-0.5 text-xs font-semibold
                {{ $n->estado == 'activo' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                {{ ucfirst($n->estado ?? '—') }}
              </span>
            </td>
            <td class="px-5 py-3 truncate max-w-[420px]" title="{{ $n->texto }}">{{ Str::limit($n->texto, 140) }}</td>
            <td class="px-5 py-3 text-right text-gray-500 dark:text-slate-300">
              <button class="ml-3 hover:text-amber-600"
                      @click="window.dispatchEvent(new CustomEvent('open-editar-nota', { detail: {
                        id_nota: {{ $n->id_nota }},
                        texto: @js($n->texto),
                        fecha_hora_local: '{{ optional($n->fecha_hora)->format('Y-m-d\TH:i') }}',
                        estado: '{{ $n->estado }}',
                        update_url: '{{ route('notas.update', $n) }}'
                      }}))">
                <i class="fa-solid fa-pen"></i>
              </button>
              <button class="ml-3 hover:text-rose-600"
                      @click="window.dispatchEvent(new CustomEvent('open-eliminar-nota', { detail: {
                        delete_url: '{{ route('notas.destroy', $n) }}'
                      }}))">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-5 py-6 text-center text-gray-500">Sin notas registradas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ==================== EMPRESAS ==================== --}}
<div class="mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fa-solid fa-building text-indigo-500"></i>
      Empresas
    </h2>
    <button
      @click="window.dispatchEvent(new CustomEvent('open-crear-empresa'))"
      class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transform transition hover:scale-105">
      <i class="fa-solid fa-plus mr-1"></i> Añadir Empresa
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">Nombre</th>
          <th class="px-5 py-3 text-left">CIF/NIF</th>
          <th class="px-5 py-3 text-left">Ámbito</th>
          <th class="px-5 py-3 text-left">Actividad</th>
          <th class="px-5 py-3 text-left">Contacto</th>
          <th class="px-5 py-3 text-left">Email</th>
          <th class="px-5 py-3 text-left">Teléfono</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($companies as $c)
          <tr>
            <td class="px-5 py-3">{{ $c->nombre }}</td>
            <td class="px-5 py-3 whitespace-nowrap">{{ $c->cif_nif }}</td>
            <td class="px-5 py-3">{{ $c->ambito ?: '—' }}</td>
            <td class="px-5 py-3">{{ $c->actividad ?: '—' }}</td>
            <td class="px-5 py-3">{{ $c->contacto_nombre ?: '—' }}</td>
            <td class="px-5 py-3">{{ $c->contacto_email ?: '—' }}</td>
            <td class="px-5 py-3">{{ $c->contacto_tel ?: '—' }}</td>
            <td class="px-5 py-3 text-right text-gray-500 dark:text-slate-300">
              <button class="ml-3 hover:text-amber-600"
                @click="window.dispatchEvent(new CustomEvent('open-editar-empresa', { detail: {
                  id: {{ $c->id }},
                  nombre: @js($c->nombre),
                  cif_nif: @js($c->cif_nif),
                  ambito: @js($c->ambito),
                  actividad: @js($c->actividad),
                  contacto_nombre: @js($c->contacto_nombre),
                  contacto_email: @js($c->contacto_email),
                  contacto_tel: @js($c->contacto_tel),
                  update_url: '{{ route('updatecompany', $c) }}'
                }}))">
                <i class="fa-solid fa-pen"></i>
              </button>
              <button class="ml-3 hover:text-rose-600"
                @click="window.dispatchEvent(new CustomEvent('open-eliminar-empresa', { detail: {
                  delete_url: '{{ route('deletecompany', $c) }}'
                }}))">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="px-5 py-6 text-center text-gray-500">No hay empresas registradas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ==================== CONVENIOS ==================== --}}
<div class="mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fa-solid fa-file-signature text-indigo-500"></i>
      Convenios
    </h2>
    <button
      @click="window.dispatchEvent(new CustomEvent('open-crear-convenio'))"
      class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transform transition hover:scale-105">
      <i class="fa-solid fa-plus mr-1"></i> Añadir Convenio
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">Empresa</th>
          <th class="px-5 py-3 text-left">Fecha firma</th>
          <th class="px-5 py-3 text-left">Validez</th>
          <th class="px-5 py-3 text-left">Firmado agencia</th>
          <th class="px-5 py-3 text-left">Firmado empresa</th>
          <th class="px-5 py-3 text-left">Documento</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($agreements as $a)
          <tr>
            <td class="px-5 py-3">{{ $a->company->nombre ?? '—' }}</td>
            <td class="px-5 py-3 whitespace-nowrap">{{ optional($a->fecha_firma)->format('d/m/Y') ?: '—' }}</td>
            <td class="px-5 py-3 whitespace-nowrap">
              @if($a->validez_desde || $a->validez_hasta)
                {{ optional($a->validez_desde)->format('d/m/Y') ?: '—' }} → {{ optional($a->validez_hasta)->format('d/m/Y') ?: '—' }}
              @else
                — 
              @endif
            </td>
            <td class="px-5 py-3">{{ $a->firmado_agencia ? 'Sí' : 'No' }}</td>
            <td class="px-5 py-3">{{ $a->firmado_empresa ? 'Sí' : 'No' }}</td>
            <td class="px-5 py-3 whitespace-nowrap">
              @if($a->pdf_doc_id)
                <a href="{{ route('documents.show', $a->pdf_doc_id) }}" target="_blank" class="text-indigo-600 hover:underline">
                  Ver PDF
                </a>
                <a href="{{ route('documents.download', $a->pdf_doc_id) }}" class="text-slate-600 hover:underline ml-3">
                  Descargar
                </a>
              @else
                <span class="text-gray-400">—</span>
              @endif
            </td>
            <td class="px-5 py-3 text-right text-gray-500 dark:text-slate-300">
              <button class="ml-3 hover:text-amber-600"
                @click="window.dispatchEvent(new CustomEvent('open-editar-convenio', { detail: {
                  id: {{ $a->id }},
                  company_id: {{ $a->company_id ?? 'null' }},
                  fecha_firma: '{{ optional($a->fecha_firma)->format('Y-m-d') }}',
                  validez_desde: '{{ optional($a->validez_desde)->format('Y-m-d') }}',
                  validez_hasta: '{{ optional($a->validez_hasta)->format('Y-m-d') }}',
                  firmado_agencia: {{ $a->firmado_agencia ? 'true' : 'false' }},
                  firmado_empresa: {{ $a->firmado_empresa ? 'true' : 'false' }},
                  pdf_doc_id: {{ $a->pdf_doc_id ?? 'null' }},
                  update_url: '{{ route('agreements.update', $a) }}'
                }}))">
                <i class="fa-solid fa-pen"></i>
              </button>
              <button class="ml-3 hover:text-rose-600"
                @click="window.dispatchEvent(new CustomEvent('open-eliminar-convenio', { detail: {
                  delete_url: '{{ route('agreements.destroy', $a) }}'
                }}))">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-5 py-6 text-center text-gray-500">No hay convenios registrados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ==================== CV DEL PARTICIPANTE ==================== --}}
<div class="mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fa-solid fa-file-lines text-indigo-500"></i>
      CV del participante
    </h2>
    <button
      @click="window.dispatchEvent(new CustomEvent('open-crear-cv'))"
      class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transition">
      <i class="fa-solid fa-upload mr-1"></i> Subir CV
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">#ID</th>
          <th class="px-5 py-3 text-left">Archivo</th>
          <th class="px-5 py-3 text-left">Fecha</th>
          <th class="px-5 py-3 text-left">Protegido</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($cvDocs as $cv)
          <tr>
            <td class="px-5 py-3">#{{ $cv->id }}</td>
            <td class="px-5 py-3">{{ $cv->nombre_archivo ?? basename($cv->hash) }}</td>
            <td class="px-5 py-3">{{ optional($cv->fecha)->format('d/m/Y H:i') }}</td>
            <td class="px-5 py-3">
              @if($cv->protegido)
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700">Sí</span>
              @else
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-gray-100 text-gray-700">No</span>
              @endif
            </td>
            <td class="px-5 py-3 text-right">
              <a href="{{ route('cvs.show', $cv) }}" target="_blank" class="mr-3 hover:text-indigo-600" title="Ver"><i class="fa-regular fa-eye"></i></a>
              <a href="{{ route('cvs.download', $cv) }}" class="mr-3 hover:text-slate-700" title="Descargar"><i class="fa-solid fa-download"></i></a>

              <button class="mr-3 hover:text-amber-700" title="Editar"
                @click="window.dispatchEvent(new CustomEvent('open-editar-cv', { detail: {
                  id: {{ $cv->id }},
                  nombre_archivo: @js($cv->nombre_archivo),
                  protegido: {{ $cv->protegido ? 'true' : 'false' }},
                  update_url: '{{ route('cvs.update', $cv) }}'
                }}))">
                <i class="fa-solid fa-pen"></i>
              </button>

              <button class="hover:text-rose-600" title="Eliminar"
                @click="window.dispatchEvent(new CustomEvent('open-eliminar-cv', { detail: {
                  delete_url: '{{ route('cvs.destroy', $cv) }}',
                  protegido: {{ $cv->protegido ? 'true' : 'false' }}
                }}))">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-5 py-6 text-center text-gray-500">Sin CVs registrados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ==================== OFERTAS DE EMPLEO ==================== --}}
<div class="mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fa-solid fa-briefcase text-indigo-500"></i>
      Ofertas de empleo
    </h2>
    <button
      @click="window.dispatchEvent(new CustomEvent('open-crear-offer'))"
      class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transition">
      <i class="fa-solid fa-plus mr-1"></i> Crear Oferta
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">Fecha</th>
          <th class="px-5 py-3 text-left">Empresa</th>
          <th class="px-5 py-3 text-left">Puesto</th>
          <th class="px-5 py-3 text-left">Contrato</th>
          <th class="px-5 py-3 text-left">Jornada</th>
          <th class="px-5 py-3 text-left">Ubicación</th>
          <th class="px-5 py-3 text-left">Estado</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($offers as $o)
          <tr>
            <td class="px-5 py-3 whitespace-nowrap">{{ optional($o->fecha)->format('d/m/Y') ?: '—' }}</td>
            <td class="px-5 py-3">{{ $o->company->nombre ?? '—' }}</td>
            <td class="px-5 py-3">{{ $o->puesto }}</td>
            <td class="px-5 py-3">{{ $o->tipo_contrato ?: '—' }}</td>
            <td class="px-5 py-3">{{ $o->jornada_pct ? ($o->jornada_pct.'%') : '—' }}</td>
            <td class="px-5 py-3">{{ $o->ubicacion ?: '—' }}</td>
            <td class="px-5 py-3">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                {{ ($o->estado ?? '') === 'activa' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $o->estado ?: '—' }}
              </span>
            </td>
            <td class="px-5 py-3 text-right">
              <a class="mr-3 hover:text-indigo-600" href="{{ route('offers.show', $o) }}" title="Ver"><i class="fa-regular fa-eye"></i></a>

              <button class="mr-3 hover:text-amber-700" title="Editar"
                @click="window.dispatchEvent(new CustomEvent('open-editar-offer', { detail: {
                  id: {{ $o->id }},
                  company_id: {{ $o->company_id }},
                  puesto: @js($o->puesto),
                  tipo_contrato: @js($o->tipo_contrato),
                  jornada_pct: @js($o->jornada_pct),
                  ubicacion: @js($o->ubicacion),
                  requisitos: @js($o->requisitos),
                  estado: @js($o->estado),
                  fecha: '{{ optional($o->fecha)->format('Y-m-d') }}',
                  update_url: '{{ route('offers.update', $o) }}'
                }}))">
                <i class="fa-solid fa-pen"></i>
              </button>

              <button class="hover:text-rose-600" title="Eliminar"
                @click="window.dispatchEvent(new CustomEvent('open-eliminar-offer', { detail: {
                  delete_url: '{{ route('offers.destroy', $o) }}'
                }}))">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="px-5 py-6 text-center text-gray-500">Sin ofertas registradas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ==================== CONTRATOS LABORALES ==================== --}}
<div class="mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fa-solid fa-file-contract text-indigo-500"></i>
      Contratos laborales
    </h2>
    <button
      @click="window.dispatchEvent(new CustomEvent('open-crear-contrato'))"
      class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transition">
      <i class="fa-solid fa-plus mr-1"></i> Crear contrato
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">Inicio</th>
          <th class="px-5 py-3 text-left">Fin prevista</th>
          <th class="px-5 py-3 text-left">Empresa</th>
          <th class="px-5 py-3 text-left">Oferta/Puesto</th>
          <th class="px-5 py-3 text-left">Tipo</th>
          <th class="px-5 py-3 text-left">% Jornada</th>
          <th class="px-5 py-3 text-left">Contrata</th>
          <th class="px-5 py-3 text-left">Docs</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($contracts as $c)
          <tr>
            <td class="px-5 py-3 whitespace-nowrap">{{ optional($c->fecha_inicio)->format('d/m/Y') ?: '—' }}</td>
            <td class="px-5 py-3 whitespace-nowrap">{{ optional($c->fecha_fin_prevista)->format('d/m/Y') ?: '—' }}</td>
            <td class="px-5 py-3">{{ $c->company->nombre ?? '—' }}</td>
            <td class="px-5 py-3">{{ $c->offer?->puesto ?? '—' }}</td>
            <td class="px-5 py-3">{{ $c->tipo_contrato ?: '—' }}</td>
            <td class="px-5 py-3">{{ $c->jornada_pct ? ($c->jornada_pct.'%') : '—' }}</td>
            <td class="px-5 py-3">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $c->registrado_contrata ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $c->registrado_contrata ? 'Sí' : 'No' }}
              </span>
            </td>
            <td class="px-5 py-3 whitespace-nowrap">
              @php $hasAny = $c->contrata_doc_id || $c->alta_ss_doc_id; @endphp
              @if($c->contrata_doc_id)
                <a href="{{ route('documents.show', $c->contrata_doc_id) }}" target="_blank" class="text-indigo-600 hover:underline">Contrata</a>
              @endif
              @if($c->alta_ss_doc_id)
                <a href="{{ route('documents.show', $c->alta_ss_doc_id) }}" target="_blank" class="text-slate-600 hover:underline ml-2">Alta SS</a>
              @endif
              @unless($hasAny)
                <span class="text-gray-400">—</span>
              @endunless
            </td>
            <td class="px-5 py-3 text-right">
              <button class="mr-3 hover:text-amber-700" title="Editar"
                @click="window.dispatchEvent(new CustomEvent('open-editar-contrato', { detail: {
                  id: {{ $c->id }},
                  participant_id: {{ (int)$participant->id }},
                  company_id: {{ $c->company_id ?? 'null' }},
                  offer_id: {{ $c->offer_id ?? 'null' }},
                  fecha_inicio: '{{ optional($c->fecha_inicio)->format('Y-m-d') }}',
                  fecha_fin_prevista: '{{ optional($c->fecha_fin_prevista)->format('Y-m-d') }}',
                  tipo_contrato: @js($c->tipo_contrato),
                  jornada_pct: @js($c->jornada_pct),
                  registrado_contrata: {{ $c->registrado_contrata ? 'true' : 'false' }},
                  contrata_doc_id: {{ $c->contrata_doc_id ?? 'null' }},
                  alta_ss_doc_id: {{ $c->alta_ss_doc_id ?? 'null' }},
                  update_url: '{{ route('contracts.update', $c) }}'
                }}))">
                <i class="fa-solid fa-pen"></i>
              </button>

              <button class="hover:text-rose-600" title="Eliminar"
                @click="window.dispatchEvent(new CustomEvent('open-eliminar-contrato', { detail: {
                  delete_url: '{{ route('contracts.destroy', $c) }}'
                }}))">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="px-5 py-6 text-center text-gray-500">Sin contratos registrados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
{{-- ==================== CANDIDATURAS ==================== --}}
<div class="mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fa-solid fa-user-check text-indigo-500"></i>
      Candidaturas
    </h2>
    <button
      @click="window.dispatchEvent(new CustomEvent('open-crear-app'))"
      class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transition">
      <i class="fa-solid fa-plus mr-1"></i> Crear candidatura
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">Fecha</th>
          <th class="px-5 py-3 text-left">Oferta</th>
          <th class="px-5 py-3 text-left">Empresa</th>
          <th class="px-5 py-3 text-left">Estado</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($applications as $a)
          <tr>
            <td class="px-5 py-3 whitespace-nowrap">{{ optional($a->fecha)->format('d/m/Y') ?: '—' }}</td>
            <td class="px-5 py-3">{{ $a->offer->puesto ?? '—' }}</td>
            <td class="px-5 py-3">{{ $a->offer->company->nombre ?? '—' }}</td>
            <td class="px-5 py-3">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $a->estado_badge_classes ?? '' }}">
                {{ ucfirst(str_replace('_',' ',$a->estado ?? '—')) }}
              </span>
            </td>
            <td class="px-5 py-3 text-right">
              <button class="mr-3 hover:text-amber-700" title="Editar"
                @click="window.dispatchEvent(new CustomEvent('open-editar-app', { detail: {
                  id: {{ $a->id }},
                  participant_id: {{ (int)$participant->id }},
                  offer_id: {{ $a->offer_id ?? 'null' }},
                  estado: @js($a->estado),
                  fecha: '{{ optional($a->fecha)->format('Y-m-d') }}',
                  update_url: '{{ route('applications.update', $a->id) }}'
                }}))">
                <i class="fa-solid fa-pen"></i>
              </button>

              <button class="hover:text-rose-600" title="Eliminar"
                @click="window.dispatchEvent(new CustomEvent('open-eliminar-app', { detail: {
                  delete_url: '{{ route('applications.destroy', $a->id) }}'
                }}))">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-5 py-6 text-center text-gray-500">Sin candidaturas registradas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ==================== SEGURIDAD SOCIAL ==================== --}}
{{-- ==================== SEGURIDAD SOCIAL (SSRecord) ==================== --}}
<div class="mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fa-solid fa-shield-heart text-indigo-500"></i>
      Seguridad Social
    </h2>
    <button
      @click="window.dispatchEvent(new CustomEvent('open-crear-ss'))"
      class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transition">
      <i class="fa-solid fa-plus mr-1"></i> Añadir registro
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">Régimen</th>
          <th class="px-5 py-3 text-left">Días alta</th>
          <th class="px-5 py-3 text-left">Jornadas reales</th>
          <th class="px-5 py-3 text-left">Coef.</th>
          <th class="px-5 py-3 text-left">Días equivalentes</th>
          <th class="px-5 py-3 text-left">Observaciones</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse(($ssRecords ?? collect()) as $ss)
          <tr>
            <td class="px-5 py-3">{{ $ss->regimen ?? '—' }}</td>
            <td class="px-5 py-3">{{ $ss->dias_alta ?? '0' }}</td>
            <td class="px-5 py-3">{{ $ss->jornadas_reales ?? '0' }}</td>
            <td class="px-5 py-3">{{ $ss->coef_aplicado ?? '—' }}</td>
            <td class="px-5 py-3">{{ $ss->dias_equivalentes ?? '0' }}</td>
            <td class="px-5 py-3 truncate max-w-[360px]" title="{{ $ss->observaciones }}">
              {{ \Illuminate\Support\Str::limit($ss->observaciones, 120) ?: '—' }}
            </td>
            <td class="px-5 py-3 text-right">
              <button class="mr-3 hover:text-amber-700" title="Editar"
                @click="window.dispatchEvent(new CustomEvent('open-editar-ss', { detail: {
                  id: {{ $ss->id }},
                  participant_id: {{ (int)$participant->id }},
                  regimen: @js($ss->regimen),
                  dias_alta: @js($ss->dias_alta),
                  jornadas_reales: @js($ss->jornadas_reales),
                  coef_aplicado: @js($ss->coef_aplicado),
                  dias_equivalentes: @js($ss->dias_equivalentes),
                  observaciones: @js($ss->observaciones),
                  update_url: '{{ route('ss.update', $ss) }}'
                }}))">
                <i class="fa-solid fa-pen"></i>
              </button>
              <button class="hover:text-rose-600" title="Eliminar"
                @click="window.dispatchEvent(new CustomEvent('open-eliminar-ss', { detail: {
                  delete_url: '{{ route('ss.destroy', $ss) }}'
                }}))">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-5 py-6 text-center text-gray-500">Sin registros de Seguridad Social.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ==================== SEGUIMIENTO LABORAL (Insertion Checks) ==================== --}}
<div class="mt-10 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-700 p-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300 flex items-center gap-2">
      <i class="fa-solid fa-briefcase text-indigo-500"></i>
      Seguimiento laboral
    </h2>
    <button
      @click="window.dispatchEvent(new CustomEvent('open-crear-ic'))"
      class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white shadow hover:bg-indigo-700 transition">
      <i class="fa-solid fa-plus mr-1"></i> Añadir seguimiento
    </button>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50 dark:bg-slate-700/60 text-gray-600 dark:text-slate-200">
        <tr>
          <th class="px-5 py-3 text-left">Fecha</th>
          <th class="px-5 py-3 text-left">Días válidos</th>
          <th class="px-5 py-3 text-left">Fuente</th>
          <th class="px-5 py-3 text-left">% Parcialidad</th>
          <th class="px-5 py-3 text-left">Periodo válido</th>
          <th class="px-5 py-3 text-left">90 días</th>
          <th class="px-5 py-3 text-left">Obs</th>
          <th class="px-5 py-3 text-right">Acciones</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($insertionChecks as $ic)
          <tr>
            <td class="px-5 py-3 whitespace-nowrap">{{ optional($ic->fecha)->format('d/m/Y') ?: '—' }}</td>
            <td class="px-5 py-3">{{ $ic->dias_validos ?? '—' }}</td>
            <td class="px-5 py-3">{{ $ic->fuente ?? '—' }}</td>
            <td class="px-5 py-3">{{ $ic->parcialidad !== null ? ($ic->parcialidad.'%') : '—' }}</td>
            <td class="px-5 py-3">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $ic->periodo_valido ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $ic->periodo_valido ? 'Sí' : 'No' }}
              </span>
            </td>
            <td class="px-5 py-3">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $ic->valido_90_dias ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $ic->valido_90_dias ? 'Sí' : 'No' }}
              </span>
            </td>
            <td class="px-5 py-3 truncate max-w-[320px]" title="{{ $ic->observaciones }}">{{ \Illuminate\Support\Str::limit($ic->observaciones, 80) }}</td>
            <td class="px-5 py-3 text-right">
              {{-- acciones (ver bloque sección 4) --}}
              <button class="ml-3 hover:text-amber-600"
                @click="window.dispatchEvent(new CustomEvent('open-editar-ic', { detail: {
                  id: {{ $ic->id }},
                  fecha: '{{ optional($ic->fecha)->format('Y-m-d') }}',
                  dias_validos: {{ $ic->dias_validos ?? 0 }},
                  fuente: @js($ic->fuente),
                  parcialidad: {{ $ic->parcialidad ?? 0 }},
                  periodo_valido: {{ $ic->periodo_valido ? 'true' : 'false' }},
                  valido_90_dias: {{ $ic->valido_90_dias ? 'true' : 'false' }},
                  observaciones: @js($ic->observaciones),
                  observaciones2: @js($ic->observaciones2),
                  update_url: '{{ route('insertion_checks.update', $ic) }}'
                }}))">
                <i class="fa-solid fa-pen"></i>
              </button>

              <button class="ml-3 hover:text-rose-600"
                @click="window.dispatchEvent(new CustomEvent('open-eliminar-ic', { detail: {
                  delete_url: '{{ route('insertion_checks.destroy', $ic) }}'
                }}))">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="px-5 py-6 text-center text-gray-500">Sin seguimientos registrados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>




{{-- ==================== MODALES: INSERTION CHECKS ==================== --}}

{{-- ==================== MODALES: INSERTION CHECKS (MEJORADAS) ==================== --}}
{{-- === Modal: Crear Seguimiento Laboral === --}}
<div x-data="{ open:false }" x-on:open-crear-ic.window="open=true" x-cloak>
  <div x-show="open" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="open" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-2xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl ring-1 ring-black/5">
      {{-- Header --}}
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700 bg-gradient-to-r from-indigo-600/10 to-purple-600/10">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-briefcase-clock text-indigo-600"></i>
          <h3 class="text-lg font-semibold text-indigo-800 dark:text-indigo-300">Nuevo seguimiento laboral</h3>
        </div>
        <button @click="open=false" class="text-slate-500 hover:text-slate-700">
          <i class="fa-solid fa-xmark text-xl"></i>
        </button>
      </div>

      {{-- Form --}}
      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          // atamos el seguimiento al participante actual:
          fd.append('participant_id','{{ $participant->id }}');
          fetch('{{ route('insertion_checks.store') }}', {
            method:'POST',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: fd
          })
          .then(async r => { if(!r.ok){ let m='No se pudo crear el seguimiento'; try{const j=await r.json(); if(j?.message) m=j.message;}catch{} throw new Error(m);} })
          .then(()=>{ open=false; showToast('Seguimiento creado ✅', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e=> showToast(e.message||'Error al crear', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Fecha</label>
            <div class="relative">
              <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="date" name="fecha" class="w-full border rounded-lg pl-10 pr-3 py-2" required>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Días válidos</label>
            <div class="relative">
              <i class="fa-solid fa-clock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="number" name="dias_validos" min="0" class="w-full border rounded-lg pl-10 pr-3 py-2" placeholder="p. ej. 90">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Fuente</label>
            <div class="relative">
              <i class="fa-solid fa-database absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="text" name="fuente" maxlength="20" class="w-full border rounded-lg pl-10 pr-3 py-2" placeholder="SEPE, TGSS, etc.">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Parcialidad (%)</label>
            <div class="relative">
              <i class="fa-solid fa-percent absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="number" name="parcialidad" min="0" max="100" class="w-full border rounded-lg pl-10 pr-3 py-2" placeholder="0–100">
            </div>
          </div>

          <div class="flex items-center gap-2">
            <input id="periodo_valido_new" type="checkbox" name="periodo_valido" value="1" class="rounded border-gray-300">
            <label for="periodo_valido_new" class="text-sm">Periodo válido</label>
          </div>

          <div class="flex items-center gap-2">
            <input id="valido90_new" type="checkbox" name="valido_90_dias" value="1" class="rounded border-gray-300">
            <label for="valido90_new" class="text-sm">Válido 90 días</label>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Observaciones</label>
            <textarea name="observaciones" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="Notas…"></textarea>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Observaciones (internas)</label>
            <textarea name="observaciones2" rows="2" class="w-full border rounded-lg px-3 py-2" placeholder="Solo equipo…"></textarea>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-4">
          <button type="button" @click="open=false" class="px-4 py-2 rounded-lg border hover:bg-slate-50">
            <i class="fa-solid fa-circle-xmark mr-1"></i> Cancelar
          </button>
          <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-md hover:shadow-lg transition">
            <i class="fa-solid fa-floppy-disk mr-1"></i> Crear
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- === Modal: Editar Seguimiento Laboral === --}}
<div x-data="{ open:false, ic:{} }" x-on:open-editar-ic.window="ic=$event.detail; open=true" x-cloak>
  <div x-show="open" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="open" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-2xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl ring-1 ring-black/5">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700 bg-gradient-to-r from-indigo-600/10 to-purple-600/10">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-pen-to-square text-indigo-600"></i>
          <h3 class="text-lg font-semibold text-indigo-800 dark:text-indigo-300">Editar seguimiento</h3>
        </div>
        <button @click="open=false" class="text-slate-500 hover:text-slate-700">
          <i class="fa-solid fa-xmark text-xl"></i>
        </button>
      </div>

      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('_method','PUT');
          // por validación servidor
          fd.append('participant_id','{{ $participant->id }}');
          fetch(ic.update_url, {
            method:'POST',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: fd
          })
          .then(async r => { if(!r.ok){ let m='No se pudo actualizar el seguimiento'; try{const j=await r.json(); if(j?.message) m=j.message;}catch{} throw new Error(m);} })
          .then(()=>{ open=false; showToast('Seguimiento actualizado ✨', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e=> showToast(e.message||'Error al actualizar', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Fecha</label>
            <div class="relative">
              <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="date" name="fecha" x-model="ic.fecha" class="w-full border rounded-lg pl-10 pr-3 py-2" required>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Días válidos</label>
            <div class="relative">
              <i class="fa-solid fa-clock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="number" name="dias_validos" min="0" x-model="ic.dias_validos" class="w-full border rounded-lg pl-10 pr-3 py-2">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Fuente</label>
            <div class="relative">
              <i class="fa-solid fa-database absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="text" name="fuente" maxlength="20" x-model="ic.fuente" class="w-full border rounded-lg pl-10 pr-3 py-2">
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Parcialidad (%)</label>
            <div class="relative">
              <i class="fa-solid fa-percent absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
              <input type="number" name="parcialidad" min="0" max="100" x-model="ic.parcialidad" class="w-full border rounded-lg pl-10 pr-3 py-2">
            </div>
          </div>

          <div class="flex items-center gap-2">
            <input id="periodo_valido_edit" type="checkbox" name="periodo_valido" value="1" class="rounded border-gray-300" :checked="!!ic.periodo_valido" @change="ic.periodo_valido=$event.target.checked">
            <label for="periodo_valido_edit" class="text-sm">Periodo válido</label>
          </div>

          <div class="flex items-center gap-2">
            <input id="valido90_edit" type="checkbox" name="valido_90_dias" value="1" class="rounded border-gray-300" :checked="!!ic.valido_90_dias" @change="ic.valido_90_dias=$event.target.checked">
            <label for="valido90_edit" class="text-sm">Válido 90 días</label>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Observaciones</label>
            <textarea name="observaciones" rows="3" x-model="ic.observaciones" class="w-full border rounded-lg px-3 py-2"></textarea>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Observaciones (internas)</label>
            <textarea name="observaciones2" rows="2" x-model="ic.observaciones2" class="w-full border rounded-lg px-3 py-2"></textarea>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-4">
          <button type="button" @click="open=false" class="px-4 py-2 rounded-lg border hover:bg-slate-50">
            <i class="fa-solid fa-circle-xmark mr-1"></i> Cancelar
          </button>
          <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-md hover:shadow-lg transition">
            <i class="fa-solid fa-floppy-disk mr-1"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- === Modal: Eliminar Seguimiento Laboral === --}}
<div x-data="{ open:false, delete_url:'' }" x-on:open-eliminar-ic.window="delete_url=$event.detail.delete_url; open=true" x-cloak>
  <div x-show="open" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden ring-1 ring-black/5">
      <div class="px-6 py-4 border-b dark:border-slate-700 bg-gradient-to-r from-rose-600/10 to-orange-600/10">
        <div class="flex items-center gap-2">
          <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
          <h3 class="text-lg font-semibold text-rose-700 dark:text-rose-300">Confirmar eliminación</h3>
        </div>
      </div>

      <div class="px-6 py-5">
        <p class="text-sm text-slate-600 dark:text-slate-300">¿Seguro que deseas eliminar este seguimiento? Esta acción no se puede deshacer.</p>
      </div>

      <form x-data
        @submit.prevent="
          const fd = new FormData();
          fd.append('_token','{{ csrf_token() }}');
          fd.append('_method','DELETE');
          fetch(delete_url, {
            method:'POST',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: fd
          })
          .then(r => { if(!r.ok && r.status!==204) throw new Error('No se pudo eliminar'); })
          .then(()=>{ open=false; showToast('Seguimiento eliminado 🗑️', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e=> showToast(e.message||'Error al eliminar', false));
        "
        class="px-6 pb-6">
        <div class="flex justify-end gap-2">
          <button type="button" @click="open=false" class="px-4 py-2 rounded-lg border hover:bg-slate-50">
            <i class="fa-solid fa-circle-xmark mr-1"></i> Cancelar
          </button>
          <button type="submit" class="px-4 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700 shadow-md hover:shadow-lg transition">
            <i class="fa-solid fa-trash-can mr-1"></i> Eliminar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>





{{-- ==================== MODALES: SEGURIDAD SOCIAL ==================== --}}

{{-- Crear SS --}}
{{-- Crear SS --}}
<div x-data="{ isOpen:false }" x-on:open-crear-ss.window="isOpen = true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-2xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Añadir registro de S.S.</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('participant_id','{{ $participant->id }}');
          fetch('{{ route('ss.store') }}', {
            method:'POST',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: fd
          })
          .then(async r => {
            if(!r.ok){
              let m='No se pudo crear el registro de S.S.';
              try{ const j=await r.json(); if(j?.message) m=j.message; }catch{}
              throw new Error(m);
            }
          })
          .then(()=>{ isOpen=false; showToast('Registro S.S. creado', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e=> showToast(e.message||'Error al crear registro', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Régimen</label>
            <input type="text" name="regimen" required class="w-full border rounded-lg px-3 py-2" placeholder="General, Agrario...">
          </div>
          <div>
            <label class="block text-sm mb-1">Días alta</label>
            <input type="number" name="dias_alta" min="0" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Jornadas reales</label>
            <input type="number" name="jornadas_reales" min="0" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Coef. aplicado</label>
            <input type="number" step="0.0001" name="coef_aplicado" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Días equivalentes</label>
            <input type="number" name="dias_equivalentes" min="0" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Observaciones</label>
            <textarea name="observaciones" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Editar SS --}}
<div x-data="{ isOpen:false, ss:{} }" x-on:open-editar-ss.window="ss=$event.detail; isOpen=true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-2xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Editar registro de S.S.</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('_method','PUT');
          fetch(ss.update_url, {
            method:'POST',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: fd
          })
          .then(async r => {
            if(!r.ok){
              let m='No se pudo actualizar el registro de S.S.';
              try{ const j=await r.json(); if(j?.message) m=j.message; }catch{}
              throw new Error(m);
            }
          })
          .then(()=>{ isOpen=false; showToast('Registro S.S. actualizado', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e=> showToast(e.message||'Error al actualizar', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Régimen</label>
            <input type="text" name="regimen" x-model="ss.regimen" required class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Días alta</label>
            <input type="number" name="dias_alta" x-model="ss.dias_alta" min="0" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Jornadas reales</label>
            <input type="number" name="jornadas_reales" x-model="ss.jornadas_reales" min="0" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Coef. aplicado</label>
            <input type="number" step="0.0001" name="coef_aplicado" x-model="ss.coef_aplicado" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Días equivalentes</label>
            <input type="number" name="dias_equivalentes" x-model="ss.dias_equivalentes" min="0" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Observaciones</label>
            <textarea name="observaciones" rows="3" x-model="ss.observaciones" class="w-full border rounded-lg px-3 py-2"></textarea>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Eliminar SS --}}
<div x-data="{ isOpen:false, delete_url:'' }" x-on:open-eliminar-ss.window="delete_url=$event.detail.delete_url; isOpen=true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden p-6 text-center">
      <h3 class="text-lg font-semibold mb-2">¿Eliminar registro de S.S.?</h3>
      <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer.</p>
      <form x-data
        @submit.prevent="
          const fd=new FormData(); fd.append('_token','{{ csrf_token() }}'); fd.append('_method','DELETE');
          fetch(delete_url,{method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(r => { if(!r.ok && r.status!==204) throw new Error('No se pudo eliminar el registro'); })
            .then(() => { isOpen=false; showToast('Registro S.S. eliminado', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e => showToast(e.message || 'Error al eliminar', false));
        ">
        <div class="flex justify-center gap-3">
          <button type="button" @click='isOpen=false' class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-rose-600 text-white">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>



{{-- ==================== MODALES: NOTAS ==================== --}}

{{-- Crear Nota --}}
<div x-data="{ isOpen:false }" x-on:open-crear-nota.window="isOpen = true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Crear Nueva Nota</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fetch('{{ route('notas.store') }}', {method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(async r => { if(!r.ok){ let m='Error al crear'; try{const j=await r.json(); if(j?.message) m=j.message;}catch{} throw new Error(m);} })
            .then(()=> { isOpen=false; showToast('Nota creada', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e=> showToast(e.message||'Error al crear', false));
        "
        class="px-6 py-5 space-y-5">
        <input type="hidden" name="id_participante" value="{{ $participant->id }}">
        <div>
          <label class="block text-sm mb-1">Texto</label>
          <textarea name="texto" required rows="4" class="w-full border rounded-lg px-3 py-2"></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Fecha y hora</label>
            <input type="datetime-local" name="fecha_hora" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Estado</label>
            <select name="estado" class="w-full border rounded-lg px-3 py-2">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Editar Nota --}}
<div x-data="{isEditOpen:false,note:{}}"
     x-on:open-editar-nota.window="note=$event.detail;isEditOpen=true"
     x-cloak>
  <div x-show="isEditOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isEditOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Editar Nota</h3>
        <button @click="isEditOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form x-data
        @submit.prevent="
          const fd=new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}'); fd.append('_method','PUT');
          fetch(note.update_url,{method:'POST',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},body:fd})
            .then(async r => { if(!r.ok){ let m='Error al actualizar'; try{const j=await r.json(); if(j?.message) m=j.message;}catch{} throw new Error(m);} })
            .then(()=>{isEditOpen=false;showToast('Nota actualizada', true);setTimeout(()=>window.location.reload(),350);})
            .catch(e=>showToast(e.message||'Error al actualizar', false));
        "
        class="px-6 py-5 space-y-5">
        <div>
          <label class="block text-sm mb-1">Texto</label>
          <textarea name="texto" x-model="note.texto" rows="4" required class="w-full border rounded-lg px-3 py-2"></textarea>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Fecha y hora</label>
            <input type="datetime-local" name="fecha_hora" x-model="note.fecha_hora_local" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Estado</label>
            <select name="estado" x-model="note.estado" class="w-full border rounded-lg px-3 py-2">
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isEditOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Eliminar Nota --}}
<div x-data="{isDeleteOpen:false,delete_url:''}"
     x-on:open-eliminar-nota.window="delete_url=$event.detail.delete_url;isDeleteOpen=true"
     x-cloak>
  <div x-show="isDeleteOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isDeleteOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden p-6 text-center">
      <h3 class="text-lg font-semibold mb-2">¿Eliminar esta nota?</h3>
      <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer.</p>
      <form x-data
        @submit.prevent="
          const fd=new FormData();fd.append('_token','{{ csrf_token() }}');fd.append('_method','DELETE');
          fetch(delete_url,{method:'POST',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},body:fd})
            .then(r=>{ if(!r.ok && r.status!==204) throw new Error('Error al eliminar'); })
            .then(()=>{isDeleteOpen=false;showToast('Nota eliminada', true);setTimeout(()=>window.location.reload(),350);})
            .catch(e=>showToast(e.message||'Error al eliminar', false));
        ">
        <div class="flex justify-center gap-3">
          <button type="button" @click='isDeleteOpen=false' class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-rose-600 text-white">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ==================== MODALES: EMPRESAS ==================== --}}

{{-- Crear Empresa --}}
<div x-data="{ isOpen:false }" x-on:open-crear-empresa.window="isOpen = true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Crear Empresa</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fetch('{{ route('savecompany') }}', { method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body: fd })
            .then(async r => { if(!r.ok){ let m='Error al crear empresa'; try{const j=await r.json(); if(j?.message)m=j.message;}catch{} throw new Error(m);} })
            .then(()=>{ isOpen=false; showToast('Empresa creada', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e=> showToast(e.message||'Error al crear la empresa', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Nombre</label>
            <input type="text" name="nombre" required class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">CIF/NIF</label>
            <input type="text" name="cif_nif" required class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Ámbito</label>
            <input type="text" name="ambito" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Actividad</label>
            <input type="text" name="actividad" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Contacto nombre</label>
            <input type="text" name="contacto_nombre" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Contacto email</label>
            <input type="email" name="contacto_email" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Contacto teléfono</label>
            <input type="text" name="contacto_tel" class="w-full border rounded-lg px-3 py-2">
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Editar Empresa --}}
<div x-data="{isOpen:false, empresa:{}}"
     x-on:open-editar-empresa.window="empresa=$event.detail; isOpen=true"
     x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Editar Empresa</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          // Si tu ruta espera PUT, descomenta la siguiente línea:
          // fd.append('_method','PUT');
          fetch(empresa.update_url, { method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body: fd })
            .then(async r => { if(!r.ok){ let m='Error al actualizar empresa'; try{const j=await r.json(); if(j?.message)m=j.message;}catch{} throw new Error(m);} })
            .then(()=>{ isOpen=false; showToast('Empresa actualizada', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e=> showToast(e.message||'Error al actualizar la empresa', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Nombre</label>
            <input type="text" name="nombre" x-model="empresa.nombre" required class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">CIF/NIF</label>
            <input type="text" name="cif_nif" x-model="empresa.cif_nif" required class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Ámbito</label>
            <input type="text" name="ambito" x-model="empresa.ambito" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Actividad</label>
            <input type="text" name="actividad" x-model="empresa.actividad" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Contacto nombre</label>
            <input type="text" name="contacto_nombre" x-model="empresa.contacto_nombre" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Contacto email</label>
            <input type="email" name="contacto_email" x-model="empresa.contacto_email" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Contacto teléfono</label>
            <input type="text" name="contacto_tel" x-model="empresa.contacto_tel" class="w-full border rounded-lg px-3 py-2">
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Eliminar Empresa --}}
<div x-data="{isOpen:false, delete_url:''}"
     x-on:open-eliminar-empresa.window="delete_url=$event.detail.delete_url; isOpen=true"
     x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden p-6 text-center">
      <h3 class="text-lg font-semibold mb-2">¿Eliminar esta empresa?</h3>
      <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer.</p>
      <form x-data
        @submit.prevent="
          const fd=new FormData(); fd.append('_token','{{ csrf_token() }}'); fd.append('_method','DELETE');
          fetch(delete_url,{method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(r => { if(!r.ok && r.status!==204) throw new Error('Error al eliminar la empresa'); })
            .then(() => { isOpen=false; showToast('Empresa eliminada', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e => showToast(e.message || 'Error al eliminar la empresa', false));
        ">
        <div class="flex justify-center gap-3">
          <button type="button" @click='isOpen=false' class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-rose-600 text-white">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ==================== MODALES: CONVENIOS ==================== --}}

{{-- Crear Convenio --}}
<div x-data="{ isOpen:false }" x-on:open-crear-convenio.window="isOpen = true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Crear Convenio</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fetch('{{ route('agreements.store') }}', {
            method:'POST',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: fd
          })
          .then(async r => {
            if(!r.ok){
              let m = 'Error al crear el convenio';
              try{ const j=await r.json(); if(j?.message) m=j.message; }catch{}
              throw new Error(m);
            }
          })
          .then(() => {
            isOpen=false;
            showToast('Convenio creado', true);
            setTimeout(()=>window.location.reload(),350);
          })
          .catch(e => showToast(e.message, false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Empresa</label>
            <select name="company_id" class="w-full border rounded-lg px-3 py-2">
              <option value="">—</option>
              @foreach($companies as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm mb-1">Fecha firma</label>
            <input type="date" name="fecha_firma" required class="w-full border rounded-lg px-3 py-2">
          </div>

          <div>
            <label class="block text-sm mb-1">Validez desde</label>
            <input type="date" name="validez_desde" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Validez hasta</label>
            <input type="date" name="validez_hasta" class="w-full border rounded-lg px-3 py-2">
          </div>

          <div class="flex items-center gap-2">
            <input id="agc" type="checkbox" name="firmado_agencia" value="1" class="rounded border-gray-300">
            <label for="agc" class="text-sm">Firmado por agencia</label>
          </div>
          <div class="flex items-center gap-2">
            <input id="emp" type="checkbox" name="firmado_empresa" value="1" class="rounded border-gray-300">
            <label for="emp" class="text-sm">Firmado por empresa</label>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Documento (PDF u otro) opcional</label>
            <select name="pdf_doc_id" class="w-full border rounded-lg px-3 py-2">
              <option value="">—</option>
              @foreach($documentsPdf as $d)
                <option value="{{ $d->id }}">#{{ $d->id }} — {{ $d->nombre_archivo }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Editar Convenio --}}
<div x-data="{ isOpen:false, conv:{} }"
     x-on:open-editar-convenio.window="conv=$event.detail; isOpen=true"
     x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Editar Convenio</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('_method','PUT');
          fetch(conv.update_url, {
            method:'POST',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: fd
          })
          .then(async r => {
            if(!r.ok){
              let m = 'Error al actualizar el convenio';
              try{ const j=await r.json(); if(j?.message) m=j.message; }catch{}
              throw new Error(m);
            }
          })
          .then(() => {
            isOpen=false;
            showToast('Convenio actualizado', true);
            setTimeout(()=>window.location.reload(),350);
          })
          .catch(e => showToast(e.message, false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Empresa</label>
            <select name="company_id" class="w-full border rounded-lg px-3 py-2" x-model="conv.company_id">
              <option value="">—</option>
              @foreach($companies as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm mb-1">Fecha firma</label>
            <input type="date" name="fecha_firma" class="w-full border rounded-lg px-3 py-2" x-model="conv.fecha_firma" required>
          </div>

          <div>
            <label class="block text-sm mb-1">Validez desde</label>
            <input type="date" name="validez_desde" class="w-full border rounded-lg px-3 py-2" x-model="conv.validez_desde">
          </div>
          <div>
            <label class="block text-sm mb-1">Validez hasta</label>
            <input type="date" name="validez_hasta" class="w-full border rounded-lg px-3 py-2" x-model="conv.validez_hasta">
          </div>

          <div class="flex items-center gap-2">
            <input id="agc2" type="checkbox" name="firmado_agencia" value="1" class="rounded border-gray-300" :checked="!!conv.firmado_agencia" @change="conv.firmado_agencia=$event.target.checked">
            <label for="agc2" class="text-sm">Firmado por agencia</label>
          </div>
          <div class="flex items-center gap-2">
            <input id="emp2" type="checkbox" name="firmado_empresa" value="1" class="rounded border-gray-300" :checked="!!conv.firmado_empresa" @change="conv.firmado_empresa=$event.target.checked">
            <label for="emp2" class="text-sm">Firmado por empresa</label>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Documento (PDF u otro) opcional</label>
            <select name="pdf_doc_id" class="w-full border rounded-lg px-3 py-2" x-model="conv.pdf_doc_id">
              <option value="">—</option>
              @foreach($documentsPdf as $d)
                <option value="{{ $d->id }}">#{{ $d->id }} — {{ $d->nombre_archivo }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Eliminar Convenio --}}
<div x-data="{isOpen:false, delete_url:''}"
     x-on:open-eliminar-convenio.window="delete_url=$event.detail.delete_url; isOpen=true"
     x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden p-6 text-center">
      <h3 class="text-lg font-semibold mb-2">¿Eliminar este convenio?</h3>
      <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer.</p>
      <form x-data
        @submit.prevent="
          const fd=new FormData(); fd.append('_token','{{ csrf_token() }}'); fd.append('_method','DELETE');
          fetch(delete_url,{method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(r => { if(!r.ok && r.status!==204) throw new Error('Error al eliminar el convenio'); })
            .then(() => { isOpen=false; showToast('Convenio eliminado', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e => showToast(e.message || 'Error al eliminar el convenio', false));
        ">
        <div class="flex justify-center gap-3">
          <button type="button" @click='isOpen=false' class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-rose-600 text-white">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ==================== MODALES: CV ==================== --}}

{{-- Subir CV --}}
<div x-data="{ isOpen:false }" x-on:open-crear-cv.window="isOpen = true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-lg rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Subir CV</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form x-data
        @submit.prevent="
          const form = $event.target;
          const fd = new FormData(form);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('participant_id','{{ $participant->id }}');
          fetch('{{ route('cvs.store') }}', {
              method:'POST',
              headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
              body: fd
          })
          .then(async r => {
            if(!r.ok){
              let m='No se pudo subir el CV';
              try{ const j=await r.json(); if(j?.message) m=j.message; }catch{}
              throw new Error(m);
            }
          })
          .then(()=>{ isOpen=false; showToast('CV subido', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e=> showToast(e.message||'Error subiendo CV', false));
        "
        class="px-6 py-5 space-y-5" enctype="multipart/form-data">

        <div>
          <label class="block text-sm mb-1">Archivo (PDF/DOC/DOCX... máx 20MB)</label>
          <input type="file" name="file" required class="w-full border rounded-lg px-3 py-2">
        </div>

        <div class="flex items-center gap-2">
          <input type="checkbox" id="protegido_new" name="protegido" value="1" class="rounded border-gray-300">
          <label for="protegido_new" class="text-sm">Marcar como protegido (no se podrá eliminar)</label>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Subir</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Editar CV --}}
<div x-data="{ isOpen:false, cv:{} }" x-on:open-editar-cv.window="cv=$event.detail; isOpen=true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-lg rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Editar CV</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('_method','PUT');
          fd.append('participant_id','{{ $participant->id }}');
          fetch(cv.update_url, {
            method:'POST',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body: fd
          })
          .then(async r => {
            if(!r.ok){
              let m='No se pudo actualizar el CV';
              try{ const j=await r.json(); if(j?.message) m=j.message; }catch{}
              throw new Error(m);
            }
          })
          .then(()=>{ isOpen=false; showToast('CV actualizado', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e=> showToast(e.message||'Error actualizando CV', false));
        "
        class="px-6 py-5 space-y-5" enctype="multipart/form-data">

        <div>
          <label class="block text-sm mb-1">Reemplazar archivo (opcional)</label>
          <input type="file" name="file" class="w-full border rounded-lg px-3 py-2">
          <p class="text-xs text-gray-500 mt-1" x-text="cv.nombre_archivo ? 'Actual: ' + cv.nombre_archivo : ''"></p>
        </div>

        <div class="flex items-center gap-2">
          <input type="checkbox" id="protegido_edit" name="protegido" value="1" class="rounded border-gray-300" :checked="!!cv.protegido">
          <label for="protegido_edit" class="text-sm">Protegido</label>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Eliminar CV --}}
<div x-data="{ isOpen:false, delete_url:'', protegido:false }"
     x-on:open-eliminar-cv.window="delete_url=$event.detail.delete_url; protegido=!!$event.detail.protegido; isOpen=true"
     x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden p-6 text-center">
      <h3 class="text-lg font-semibold mb-2">¿Eliminar este CV?</h3>
      <template x-if="protegido">
        <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded p-2 mb-4">
          Este CV está protegido. Debes desprotegerlo antes de poder eliminarlo.
        </p>
      </template>
      <template x-if="!protegido">
        <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer.</p>
      </template>

      <form x-data
        @submit.prevent="
          if(protegido){ isOpen=false; return; }
          const fd=new FormData(); fd.append('_token','{{ csrf_token() }}'); fd.append('_method','DELETE');
          fetch(delete_url,{method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(r => { if(!r.ok && r.status!==204) throw new Error('No se pudo eliminar el CV'); })
            .then(() => { isOpen=false; showToast('CV eliminado', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e => showToast(e.message || 'Error al eliminar CV', false));
        ">
        <div class="flex justify-center gap-3">
          <button type="button" @click='isOpen=false' class="px-4 py-2 rounded bg-gray-200">Cerrar</button>
          <button type="submit" class="px-4 py-2 rounded bg-rose-600 text-white" :class="protegido ? 'opacity-40 cursor-not-allowed' : ''" :disabled="protegido">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ==================== MODALES: OFFERS ==================== --}}

{{-- Crear Offer --}}
<div x-data="{ isOpen:false }" x-on:open-crear-offer.window="isOpen = true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Crear oferta</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fetch('{{ route('offers.store') }}', { method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body: fd })
            .then(async r => { if(!r.ok){ let m='No se pudo crear la oferta'; try{const j=await r.json(); if(j?.message)m=j.message;}catch{} throw new Error(m);} })
            .then(()=>{ isOpen=false; showToast('Oferta creada', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e=> showToast(e.message||'Error al crear la oferta', false));
        "
        class="px-6 py-5 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Empresa</label>
            <select name="company_id" class="w-full border rounded-lg px-3 py-2" required>
              @foreach($companies as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm mb-1">Fecha</label>
            <input type="date" name="fecha" required class="w-full border rounded-lg px-3 py-2">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Puesto</label>
            <input type="text" name="puesto" required class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Tipo de contrato</label>
            <input type="text" name="tipo_contrato" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Jornada (%)</label>
            <input type="number" name="jornada_pct" min="1" max="100" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Ubicación</label>
            <input type="text" name="ubicacion" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Estado</label>
            <input type="text" name="estado" placeholder="activa / cerrada ..." class="w-full border rounded-lg px-3 py-2">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Requisitos</label>
            <textarea name="requisitos" rows="4" class="w-full border rounded-lg px-3 py-2"></textarea>
          </div>
        </div>
        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Editar Offer --}}
<div x-data="{ isOpen:false, offer:{} }" x-on:open-editar-offer.window="offer=$event.detail; isOpen=true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Editar oferta</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('_method','PUT');
          fetch(offer.update_url, { method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body: fd })
            .then(async r => { if(!r.ok){ let m='No se pudo actualizar la oferta'; try{const j=await r.json(); if(j?.message)m=j.message;}catch{} throw new Error(m);} })
            .then(()=>{ isOpen=false; showToast('Oferta actualizada', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e=> showToast(e.message||'Error al actualizar la oferta', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Empresa</label>
            <select name="company_id" class="w-full border rounded-lg px-3 py-2" x-model="offer.company_id" required>
              @foreach($companies as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-sm mb-1">Fecha</label>
            <input type="date" name="fecha" x-model="offer.fecha" required class="w-full border rounded-lg px-3 py-2">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Puesto</label>
            <input type="text" name="puesto" x-model="offer.puesto" required class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Tipo de contrato</label>
            <input type="text" name="tipo_contrato" x-model="offer.tipo_contrato" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Jornada (%)</label>
            <input type="number" name="jornada_pct" min="1" max="100" x-model="offer.jornada_pct" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Ubicación</label>
            <input type="text" name="ubicacion" x-model="offer.ubicacion" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div>
            <label class="block text-sm mb-1">Estado</label>
            <input type="text" name="estado" x-model="offer.estado" class="w-full border rounded-lg px-3 py-2">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Requisitos</label>
            <textarea name="requisitos" rows="4" x-model="offer.requisitos" class="w-full border rounded-lg px-3 py-2"></textarea>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Eliminar Offer --}}
<div x-data="{ isOpen:false, delete_url:'' }" x-on:open-eliminar-offer.window="delete_url=$event.detail.delete_url; isOpen=true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden p-6 text-center">
      <h3 class="text-lg font-semibold mb-2">¿Eliminar esta oferta?</h3>
      <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer.</p>
      <form x-data
        @submit.prevent="
          const fd=new FormData(); fd.append('_token','{{ csrf_token() }}'); fd.append('_method','DELETE');
          fetch(delete_url,{method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(r => { if(!r.ok && r.status!==204) throw new Error('No se pudo eliminar la oferta'); })
            .then(() => { isOpen=false; showToast('Oferta eliminada', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e => showToast(e.message || 'Error al eliminar la oferta', false));
        ">
        <div class="flex justify-center gap-3">
          <button type="button" @click='isOpen=false' class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-rose-600 text-white">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ==================== MODALES: CONTRATOS ==================== --}}

{{-- Crear Contrato --}}
<div x-data="{ isOpen:false }" x-on:open-crear-contrato.window="isOpen = true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Crear contrato</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('participant_id','{{ $participant->id }}');
          fetch('{{ route('contracts.store') }}', { method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body: fd })
            .then(r => { if(!r.ok){ throw new Error('No se pudo crear el contrato'); } })
            .then(()=>{ isOpen=false; showToast('Contrato creado', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e=> showToast(e.message||'Error al crear el contrato', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Empresa</label>
            <select name="company_id" class="w-full border rounded-lg px-3 py-2">
              <option value="">—</option>
              @foreach($companies as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm mb-1">Oferta (opcional)</label>
            <select name="offer_id" class="w-full border rounded-lg px-3 py-2">
              <option value="">—</option>
              @foreach($offers as $o)
                <option value="{{ $o->id }}">{{ $o->puesto }} @if($o->company) — {{ $o->company->nombre }} @endif</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm mb-1">Fecha inicio</label>
            <input type="date" name="fecha_inicio" required class="w-full border rounded-lg px-3 py-2">
          </div>

          <div>
            <label class="block text-sm mb-1">Fecha fin prevista</label>
            <input type="date" name="fecha_fin_prevista" class="w-full border rounded-lg px-3 py-2">
          </div>

          <div>
            <label class="block text-sm mb-1">Tipo contrato</label>
            <input type="text" name="tipo_contrato" class="w-full border rounded-lg px-3 py-2" placeholder="Indefinido, Temporal…">
          </div>

          <div>
            <label class="block text-sm mb-1">% Jornada</label>
            <input type="number" name="jornada_pct" min="1" max="100" class="w-full border rounded-lg px-3 py-2">
          </div>

          <div class="flex items-center gap-2 md:col-span-2">
            <input id="contrata_new" type="checkbox" name="registrado_contrata" value="1" class="rounded border-gray-300">
            <label for="contrata_new" class="text-sm">Registrado en Contrat@</label>
          </div>

          <div>
            <label class="block text-sm mb-1">Doc Contrat@ (opcional)</label>
            <select name="contrata_doc_id" class="w-full border rounded-lg px-3 py-2">
              <option value="">—</option>
              @foreach($documentsPdf as $d)
                <option value="{{ $d->id }}">#{{ $d->id }} — {{ $d->nombre_archivo }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm mb-1">Doc Alta SS (opcional)</label>
            <select name="alta_ss_doc_id" class="w-full border rounded-lg px-3 py-2">
              <option value="">—</option>
              @foreach($documentsPdf as $d)
                <option value="{{ $d->id }}">#{{ $d->id }} — {{ $d->nombre_archivo }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Editar Contrato --}}
<div x-data="{ isOpen:false, ctr:{} }" x-on:open-editar-contrato.window="ctr=$event.detail; isOpen=true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-3xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Editar contrato</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('_method','PUT');
          fetch(ctr.update_url, { method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body: fd })
            .then(r => { if(!r.ok){ throw new Error('No se pudo actualizar el contrato'); } })
            .then(()=>{ isOpen=false; showToast('Contrato actualizado', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e=> showToast(e.message||'Error al actualizar el contrato', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm mb-1">Empresa</label>
            <select name="company_id" class="w-full border rounded-lg px-3 py-2" x-model="ctr.company_id">
              <option value="">—</option>
              @foreach($companies as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm mb-1">Oferta (opcional)</label>
            <select name="offer_id" class="w-full border rounded-lg px-3 py-2" x-model="ctr.offer_id">
              <option value="">—</option>
              @foreach($offers as $o)
                <option value="{{ $o->id }}">{{ $o->puesto }} @if($o->company) — {{ $o->company->nombre }} @endif</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm mb-1">Fecha inicio</label>
            <input type="date" name="fecha_inicio" x-model="ctr.fecha_inicio" required class="w-full border rounded-lg px-3 py-2">
          </div>

          <div>
            <label class="block text-sm mb-1">Fecha fin prevista</label>
            <input type="date" name="fecha_fin_prevista" x-model="ctr.fecha_fin_prevista" class="w-full border rounded-lg px-3 py-2">
          </div>

          <div>
            <label class="block text-sm mb-1">Tipo contrato</label>
            <input type="text" name="tipo_contrato" x-model="ctr.tipo_contrato" class="w-full border rounded-lg px-3 py-2">
          </div>

          <div>
            <label class="block text-sm mb-1">% Jornada</label>
            <input type="number" name="jornada_pct" min="1" max="100" x-model="ctr.jornada_pct" class="w-full border rounded-lg px-3 py-2">
          </div>

          <div class="flex items-center gap-2 md:col-span-2">
            <input id="contrata_edit" type="checkbox" name="registrado_contrata" value="1" class="rounded border-gray-300" :checked="!!ctr.registrado_contrata" @change="ctr.registrado_contrata=$event.target.checked">
            <label for="contrata_edit" class="text-sm">Registrado en Contrat@</label>
          </div>

          <div>
            <label class="block text-sm mb-1">Doc Contrat@ (opcional)</label>
            <select name="contrata_doc_id" class="w-full border rounded-lg px-3 py-2" x-model="ctr.contrata_doc_id">
              <option value="">—</option>
              @foreach($documentsPdf as $d)
                <option value="{{ $d->id }}">#{{ $d->id }} — {{ $d->nombre_archivo }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm mb-1">Doc Alta SS (opcional)</label>
            <select name="alta_ss_doc_id" class="w-full border rounded-lg px-3 py-2" x-model="ctr.alta_ss_doc_id">
              <option value="">—</option>
              @foreach($documentsPdf as $d)
                <option value="{{ $d->id }}">#{{ $d->id }} — {{ $d->nombre_archivo }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Eliminar Contrato --}}
<div x-data="{ isOpen:false, delete_url:'' }" x-on:open-eliminar-contrato.window="delete_url=$event.detail.delete_url; isOpen=true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden p-6 text-center">
      <h3 class="text-lg font-semibold mb-2">¿Eliminar este contrato?</h3>
      <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer.</p>
      <form x-data
        @submit.prevent="
          const fd=new FormData(); fd.append('_token','{{ csrf_token() }}'); fd.append('_method','DELETE');
          fetch(delete_url,{method:'POST', headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}, body:fd})
            .then(r => { if(!r.ok && r.status!==204) throw new Error('No se pudo eliminar el contrato'); })
            .then(() => { isOpen=false; showToast('Contrato eliminado', true); setTimeout(()=>window.location.reload(),350); })
            .catch(e => showToast(e.message || 'Error al eliminar el contrato', false));
        ">
        <div class="flex justify-center gap-3">
          <button type="button" @click='isOpen=false' class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-rose-600 text-white">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- Crear Candidatura --}}
<div x-data="{ isOpen:false }" x-on:open-crear-app.window="isOpen = true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-2xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Crear candidatura</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('participant_id','{{ $participant->id }}'); // <-- IMPORTANTE
          fetch('{{ route('applications.store') }}', {
              method:'POST',
              headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
              body: fd
          })
          .then(async r => { if(!r.ok){ let m='No se pudo crear la candidatura'; try{const j=await r.json(); if(j?.message)m=j.message;}catch{} throw new Error(m);} })
          .then(()=>{ isOpen=false; showToast('Candidatura creada', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e=> showToast(e.message||'Error al crear la candidatura', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Oferta</label>
            <select name="offer_id" class="w-full border rounded-lg px-3 py-2" required>
              @foreach($offers as $o)
                <option value="{{ $o->id }}">
                  {{ $o->puesto }} @if($o->company) — {{ $o->company->nombre }} @endif
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm mb-1">Fecha</label>
            <input type="date" name="fecha" required class="w-full border rounded-lg px-3 py-2">
          </div>

          <div>
            <label class="block text-sm mb-1">Estado</label>
            <select name="estado" class="w-full border rounded-lg px-3 py-2" required>
              @foreach($appEstados as $e)
                <option value="{{ $e }}">{{ ucfirst(str_replace('_',' ', $e)) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-emerald-600 text-white">Crear</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Editar Candidatura --}}
<div x-data="{ isOpen:false, app:{} }" x-on:open-editar-app.window="app=$event.detail; isOpen=true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 p-4 sm:p-6 overflow-auto">
    <div class="mx-auto w-full max-w-2xl rounded-2xl bg-white dark:bg-slate-900 shadow-2xl">
      <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-700">
        <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">Editar candidatura</h3>
        <button @click="isOpen=false"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <form x-data
        @submit.prevent="
          const fd = new FormData($event.target);
          fd.append('_token','{{ csrf_token() }}');
          fd.append('_method','PUT');
          // AÑADIMOS participant_id PARA CUMPLIR LA VALIDACIÓN
          fd.append('participant_id','{{ $participant->id }}');
          fetch(app.update_url, {
              method:'POST',
              headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
              body: fd
          })
          .then(async r => { if(!r.ok){ let m='No se pudo actualizar la candidatura'; try{const j=await r.json(); if(j?.message)m=j.message;}catch{} throw new Error(m);} })
          .then(()=>{ isOpen=false; showToast('Candidatura actualizada', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e=> showToast(e.message||'Error al actualizar la candidatura', false));
        "
        class="px-6 py-5 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm mb-1">Oferta</label>
            <select name="offer_id" class="w-full border rounded-lg px-3 py-2" x-model="app.offer_id" required>
              @foreach($offers as $o)
                <option value="{{ $o->id }}">
                  {{ $o->puesto }} @if($o->company) — {{ $o->company->nombre }} @endif
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm mb-1">Fecha</label>
            <input type="date" name="fecha" x-model="app.fecha" required class="w-full border rounded-lg px-3 py-2">
          </div>

          <div>
            <label class="block text-sm mb-1">Estado</label>
            <select name="estado" class="w-full border rounded-lg px-3 py-2" x-model="app.estado" required>
              @foreach($appEstados as $e)
                <option value="{{ $e }}">{{ ucfirst(str_replace('_',' ', $e)) }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="flex justify-end gap-2 border-t pt-3">
          <button type="button" @click="isOpen=false" class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Eliminar Candidatura --}}
<div x-data="{ isOpen:false, delete_url:'' }" x-on:open-eliminar-app.window="delete_url=$event.detail.delete_url; isOpen=true" x-cloak>
  <div x-show="isOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>
  <div x-show="isOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden p-6 text-center">
      <h3 class="text-lg font-semibold mb-2">¿Eliminar esta candidatura?</h3>
      <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer.</p>
      <form x-data
        @submit.prevent="
          const fd=new FormData();
          fd.append('_token','{{ csrf_token() }}');
          fd.append('_method','DELETE');
          fetch(delete_url,{
            method:'POST',
            headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
            body:fd
          })
          .then(r => { if(!r.ok && r.status!==204) throw new Error('No se pudo eliminar la candidatura'); })
          .then(() => { isOpen=false; showToast('Candidatura eliminada', true); setTimeout(()=>window.location.reload(),350); })
          .catch(e => showToast(e.message || 'Error al eliminar la candidatura', false));
        ">
        <div class="flex justify-center gap-3">
          <button type="button" @click='isOpen=false' class="px-4 py-2 rounded bg-gray-200">Cancelar</button>
          <button type="submit" class="px-4 py-2 rounded bg-rose-600 text-white">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- ======= TOASTS ======= --}}
<div x-data x-ref="toaster" class="fixed bottom-6 right-6 z-[120]" style="display:none"></div>

<script>
  function showToast(msg, ok=true){
    const host = document.querySelector('[x-ref=toaster]');
    if(!host) return;
    host.style.display = 'block';
    const box = document.createElement('div');
    box.className = 'mb-2 rounded-xl px-4 py-3 text-sm shadow-lg transition ' + (ok ? 'bg-emerald-600 text-white':'bg-rose-600 text-white');
    box.textContent = msg || (ok?'Hecho':'Error');
    host.appendChild(box);
    setTimeout(()=>{ box.remove(); if(!host.childElementCount){ host.style.display='none'; } }, 2200);
  }

    // Alerta bonita y grande
  function showBigAlert(msg, ok=true){
    const host = document.querySelector('[x-ref=toaster]');
    if(!host) return;
    host.style.display = 'block';
    const box = document.createElement('div');
    box.className = 'mb-2 rounded-2xl px-5 py-4 text-sm shadow-2xl ring-1 ring-black/5 transition ' + (ok ? 'bg-white text-gray-900':'bg-rose-600 text-white');
    box.innerHTML = `
      <div class="flex items-start gap-3">
        <div class="h-9 w-9 rounded-xl ${ok ? 'bg-indigo-600 text-white':'bg-white/20 text-white'} grid place-content-center">
          <i class="fa-solid fa-bell"></i>
        </div>
        <div class="min-w-[260px]">
          <div class="font-semibold mb-0.5">${ok ? 'Recordatorio de seguimiento' : 'Aviso'}</div>
          <div class="text-sm opacity-90">${msg}</div>
        </div>
        <button class="ml-2 text-gray-400 hover:text-gray-600" onclick="this.closest('div.mb-2').remove();">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
    `;
    host.appendChild(box);
    setTimeout(()=>{ box.remove(); if(!host.childElementCount){ host.style.display='none'; } }, 8000);
  }

  // A los 10s: calcula días restantes del seguimiento más reciente (fecha + dias_validos)
  window.addEventListener('DOMContentLoaded', () => {
    const anchor = document.getElementById('ic-next');
    if(!anchor) return;

    const rawFecha = anchor.dataset.fecha;  // 'YYYY-MM-DD'
    const rawDias  = parseInt(anchor.dataset.dias||'0',10);

    if(!rawFecha || !rawDias || isNaN(rawDias) || rawDias<=0) return;

    // target = fecha + dias_validos (al final del día)
    const parts = rawFecha.split('-'); // Y, M, D
    const start = new Date(parseInt(parts[0],10), parseInt(parts[1],10)-1, parseInt(parts[2],10));
    const target = new Date(start.getTime());
    target.setDate(start.getDate() + rawDias);

    const now = new Date();
    const ms = target.getTime() - now.getTime();
    const daysLeft = Math.ceil(ms / (1000*60*60*24));

    setTimeout(() => {
      if (daysLeft > 0) {
        showBigAlert(`Faltan <b>${daysLeft}</b> día(s) para que venza el seguimiento actual.`, true);
      } else if (daysLeft === 0) {
        showBigAlert(`El seguimiento vence <b>hoy</b>.`, true);
      } else {
        showBigAlert(`El seguimiento <b>ya ha vencido</b> hace ${Math.abs(daysLeft)} día(s).`, false);
      }
    }, 10000); // 10s
  });
</script>

<style>
@keyframes fadeInUp { 0% { opacity: 0; transform: translateY(20px);} 100% { opacity: 1; transform: translateY(0);} }
.animate-fadeInUp { animation: fadeInUp 0.6s ease forwards; }
@keyframes gradientX { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
.animate-gradientX { background-size: 200% 200%; animation: gradientX 5s ease infinite; }
.badge-estado { @apply inline-flex items-center rounded-full px-3 py-0.5 text-xs font-semibold; }
</style>
@endsection
