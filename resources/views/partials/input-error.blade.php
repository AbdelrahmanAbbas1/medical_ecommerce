@php
    $errorMessages = [];
    
    if (is_array($errors) && count($errors) > 0) {
        $errorMessages = $errors;
    } elseif (isset($errors) && method_exists($errors, 'any') && $errors->any()) {
        $errorMessages = $errors->all();
    }
@endphp

@if (count($errorMessages) > 0)
    <div class="invalid-feedback d-block">
        @foreach ($errorMessages as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif
