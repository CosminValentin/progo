@extends('layouts.auth')
@section('title','Ofertas')

@section('content')
<div class="max-w-7xl mx-auto p-6">
  <div class="rounded-3xl p-8 mb-6 bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 text-white shadow-xl">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold">Ofertas</h1>
        <p class="text-white/90">Gestión de ofertas laborales.</p>
      </div>
      <a href="{{ route('offers.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white/90 text-indigo-700 font-semibold hover:bg-white transition shadow">
        ＋ Nueva oferta
      </a>
    </div>
  </div>

  @if(session('ok'))
    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 text-emerald-800 px-4 py-3">
      {{ session('ok') }}
    </div>
  @endif

  <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider">
            <th class="px-6 py-3 text-left">Puesto</th>
            <th class="px-6 py-3 text-left">Empresa</th>
            <th class="px-6 py-3 text-center">Jornada</th>
            <th class="px-6 py-3 text-center">Estado</th>
            <th class="px-6 py-3 text-center">Fecha</th>
            <th class="px-6 py-3 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($offers as $o)
          <tr class="hover:bg-indigo-50/40">
            <td class="px-6 py-4 font-semibold text-gray-900">{{ $o->puesto }}</td>
            <td class="px-6 py-4">{{ $o->company->nombre ?? '—' }}</td>
            <td class="px-6 py-4 text-center">{{ $o->jornada_pct ? $o->jornada_pct.'%' : '—' }}</td>
            <td class="px-6 py-4 text-center">
              <span class="px-2 py-1 rounded-full text-xs font-medium {{ $o->estado=='abierta' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                {{ ucfirst($o->estado ?? '—') }}
              </span>
            </td>
            <td class="px-6 py-4 text-center">{{ optional($o->fecha)->format('d/m/Y') }}</td>
            <td class="px-6 py-4 text-right">
              <div class="flex justify-end gap-2">
                <a href="{{ route('offers.show',$o) }}" class="px-3 py-1.5 rounded-xl text-indigo-700 bg-indigo-50 hover:bg-indigo-100 font-medium">Ver</a>
                <a href="{{ route('offers.edit',$o) }}" class="px-3 py-1.5 rounded-xl text-amber-700 bg-amber-50 hover:bg-amber-100 font-medium">Editar</a>
                <form method="POST" action="{{ route('offers.destroy',$o) }}" onsubmit="return confirm('¿Eliminar esta oferta?')">
                  @csrf @method('DELETE')
                  <button class="px-3 py-1.5 rounded-xl text-rose-700 bg-rose-50 hover:bg-rose-100 font-medium">Borrar</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Sin ofertas por ahora.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-6 border-t border-gray-100">{{ $offers->links() }}</div>
  </div>
</div>
@endsection
