@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-accent-500 focus:ring-accent-500 rounded-lg shadow-sm']) !!}>
