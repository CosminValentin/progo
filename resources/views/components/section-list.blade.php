@props(['title','color'=>'indigo','icon'=>'fa-list','addUrl'=>null,'items'=>collect()])

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow overflow-hidden">
  <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-slate-700">
    <div class="flex items-center gap-2 text-{{ $color }}-600 dark:text-{{ $color }}-300">
      <i class="fa-solid {{ $icon }}"></i>
      <h3 class="font-semibold text-sm uppercase">{{ $title }}</h3>
    </div>
    @if($addUrl)
      <button @click="$dispatch('modal-open',{url:'{{ $addUrl }}'})"
              class="text-xs px-3 py-1.5 rounded-lg bg-{{ $color }}-600 text-white hover:bg-{{ $color }}-700">Añadir</button>
    @endif
  </div>

  <div class="p-4 text-sm">
    @if($items->isEmpty())
      <p class="text-gray-500 text-center">Sin registros.</p>
    @else
      <ul class="divide-y divide-gray-100 dark:divide-slate-700 text-center">
        @foreach($items as $it)
          <li class="py-2 flex justify-between items-center text-center">
            <div class="w-full">
              <div class="font-medium">{{ $it->observaciones ?? $it->regimen ?? '—' }}</div>
              <div class="text-gray-500 text-xs">
                {{ optional($it->fecha ?? $it->created_at)->format('d/m/Y') }}
              </div>
            </div>
            <button @click="$dispatch('modal-open',{url:'{{ route(Str::of($title)->snake()->append('.edit'),$it) }}?return_to={{ urlencode(url()->current()) }}'})"
                    class="text-xs text-yellow-600 hover:underline">Editar</button>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</div>
