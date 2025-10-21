@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'block w-full rounded-md border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition',
    ]) !!}
/>
