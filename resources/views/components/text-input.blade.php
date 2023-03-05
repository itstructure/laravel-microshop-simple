@props(['disabled' => false, 'invalid' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control px-2' . ($invalid ? ' is-invalid' : '')]) !!}>
