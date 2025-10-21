@props(['messages'])

@if ($messages)
    <p {{ $attributes->merge(['class' => 'text-sm text-red-600 mt-1']) }}>
        {{ is_array($messages) ? implode(', ', $messages) : $messages }}
    </p>
@endif
