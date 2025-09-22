@props(['align' => 'right', 'width' => '48', 'contentClasses' => ''])

@php
$alignmentClasses = match ($align) {
    'left' => 'dropdown-menu-start',
    'top' => 'dropup',
    default => 'dropdown-menu-end',
};
@endphp

<div class="dropdown {{ $alignmentClasses }}">
    {{ $trigger }}
    <div class="dropdown-menu {{ $alignmentClasses }} {{ $contentClasses }}">
        {{ $content }}
    </div>
</div>
