@props(['title','color'=>'indigo','icon'=>'fa-table','addUrl'=>null])
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

  <div class="overflow-x-auto">
    <table class="min-w-full text-sm text-center align-middle">
      <thead class="bg-gray-50 dark:bg-slate-700/40 text-gray-700 dark:text-slate-200">
        <tr>
          {{ $headers }}
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
        {{ $slot }}
      </tbody>
    </table>
  </div>
</div>
