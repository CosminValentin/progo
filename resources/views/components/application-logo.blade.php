@props(['size' => 'h-10'])

<a href="{{ url('/') }}" class="inline-flex items-center gap-2 group">
    {{-- Logo claro --}}
    <img src="{{ asset('brand/progo-logo.png') }}" alt="PROGO" class="{{ $size }} block dark:hidden select-none" />


    {{-- Texto alternativo para accesibilidad --}}
    <span class="sr-only">{{ config('app.name', 'PROGO') }}</span>
</a>
