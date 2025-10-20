@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    @if (session('status'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Participante #{{ $participant->id }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('editparticipant',$participant->id) }}" class="rounded bg-amber-600 px-4 py-2 text-white hover:bg-amber-700">Editar</a>
            <a href="{{ route('participants') }}" class="rounded border px-4 py-2 hover:bg-gray-50">Volver</a>
        </div>
    </div>

    <div class="rounded border p-4 space-y-2">
        <div><span class="font-semibold">DNI/NIE:</span> {{ $participant->dni_nie }}</div>
        <div><span class="font-semibold">Nombre:</span> {{ $participant->nombre }}</div>
        <div><span class="font-semibold">Teléfono:</span> {{ $participant->telefono }}</div>
        <div><span class="font-semibold">Email:</span> {{ $participant->email }}</div>
        <div><span class="font-semibold">Fecha alta:</span> {{ optional($participant->fecha_alta_prog)->format('Y-m-d') }}</div>
        <div><span class="font-semibold">Provincia:</span> {{ $participant->provincia }}</div>
        <div><span class="font-semibold">Estado:</span> {{ $participant->estado }}</div>
        <div><span class="font-semibold">Consentimiento RGPD:</span> {{ $participant->consent_rgpd ? 'Sí' : 'No' }}</div>
        <div><span class="font-semibold">Notas:</span> <pre class="whitespace-pre-wrap">{{ $participant->notas }}</pre></div>
    </div>
</div>
@endsection
