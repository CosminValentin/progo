@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    @if (session('status'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Participantes</h1>
        <a href="{{ route('addparticipant') }}" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Nuevo</a>
    </div>

    <form method="GET" action="{{ route('participants') }}" class="mb-4">
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Buscar DNI/NIE, nombre o email"
                   class="w-full rounded border px-3 py-2"/>
            <button class="rounded bg-gray-800 px-4 py-2 text-white hover:bg-black">Buscar</button>
        </div>
    </form>

    <div class="overflow-x-auto rounded border">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-sm font-semibold">ID</th>
                    <th class="px-3 py-2 text-left text-sm font-semibold">DNI/NIE</th>
                    <th class="px-3 py-2 text-left text-sm font-semibold">Nombre</th>
                    <th class="px-3 py-2 text-left text-sm font-semibold">Email</th>
                    <th class="px-3 py-2 text-left text-sm font-semibold">Fecha Alta</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($participants as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-sm">{{ $p->id }}</td>
                        <td class="px-3 py-2 text-sm">{{ $p->dni_nie }}</td>
                        <td class="px-3 py-2 text-sm">{{ $p->nombre }}</td>
                        <td class="px-3 py-2 text-sm">{{ $p->email }}</td>
                        <td class="px-3 py-2 text-sm">{{ optional($p->fecha_alta_prog)->format('Y-m-d') }}</td>
                        <td class="px-3 py-2 text-sm">
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('viewparticipant',$p->id) }}" class="text-blue-700 hover:underline">Ver</a>
                                <a href="{{ route('editparticipant',$p->id) }}" class="text-amber-700 hover:underline">Editar</a>
                                <form method="POST" action="{{ route('deleteparticipant',$p->id) }}"
                                      onsubmit="return confirm('¿Eliminar participante?');">
                                    @csrf
                                    <button class="text-red-700 hover:underline">Borrar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-3 py-8 text-center text-gray-500">Sin resultados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $participants->links() }}</div>
</div>
@endsection
