{{-- select.blade.php --}}
@props([
    'label', 
    'name', 
    'options' => [], 
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
    <select 
        class="form-select" 
        name="{{ $name }}" 
        @if($readonly) disabled @endif
        @if($required) required @endif
    >
        <option value="">-- Select --</option>
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>
@endif
