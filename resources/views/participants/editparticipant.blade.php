@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6">Editar participante #{{ $participant->id }}</h1>

    <form method="POST" action="{{ route('updateparticipant',$participant->id) }}" class="space-y-4">
        @csrf
        @include('partials.participant_form', ['participant' => $participant])
        <div class="mt-6 flex gap-3">
            <button class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Guardar</button>
            <a href="{{ route('participants') }}" class="rounded border px-4 py-2 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>
@endsection
