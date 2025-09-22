@php
$label = $label ?? 'Label';
$for = $for ?? '';
$class = $class ?? 'form-label';
@endphp

<label for="{{ $for }}" class="{{ $class }}">
    {{ $label }}
</label>
