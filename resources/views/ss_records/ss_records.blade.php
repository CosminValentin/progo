@extends('layouts.app_windmill')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold">Registros SS</h1>
    <a href="{{ url('/addss_record') }}" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Nuevo</a>
  </div>

  <div class="overflow-x-auto rounded border bg-white">
    <table class="min-w-full divide-y">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-3 py-2 text-left text-sm font-semibold">ID</th>
          <th class="px-3 py-2 text-left text-sm font-semibold">Participante</th>
          <th class="px-3 py-2 text-left text-sm font-semibold">Régimen</th>
          <th class="px-3 py-2 text-left text-sm font-semibold">Días alta</th>
          <th class="px-3 py-2"></th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <tr class="hover:bg-gray-50">
          <td class="px-3 py-2 text-sm">—</td>
          <td class="px-3 py-2 text-sm">—</td>
          <td class="px-3 py-2 text-sm">—</td>
          <td class="px-3 py-2 text-sm">—</td>
          <td class="px-3 py-2 text-sm">
            <div class="flex gap-2 justify-end">
              <a href="{{ url('/viewss_record/1') }}" class="text-blue-700 hover:underline">Ver</a>
              <a href="{{ url('/editss_record/1') }}" class="text-amber-700 hover:underline">Editar</a>
              <form method="POST" action="{{ url('/deletess_record/1') }}" onsubmit="return confirm('¿Eliminar?')">
                @csrf
                <button class="text-red-700 hover:underline">Borrar</button>
              </form>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
