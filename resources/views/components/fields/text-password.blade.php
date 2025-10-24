{{-- text-password.blade.php --}}
@props([
    'label', 
    'name', 
    'readonly' => false, 
    'fieldClass' => '', 
    'required' => false, 
    'showHide' => true
])

{{-- Hide the field if showHide is false --}}
@if($showHide)
<div class="mb-3 {{ $fieldClass }}">
    <label class="form-label fw-semibold">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input
        type="password"
        name="{{ $name }}"
        value="{{ old($name) }}"
        class="form-control"
        @if($readonly) readonly @endif
        @if($required) required @endif
    >
</div>
@endif
