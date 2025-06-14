@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'flex items-center p-2 rounded-lg text-white bg-blue-600 hover:bg-blue-700 group'
            : 'flex items-center p-2 rounded-lg text-white hover:bg-gray-700 group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
