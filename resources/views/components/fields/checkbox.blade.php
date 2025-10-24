{{-- checkbox.blade.php --}}
@props([
    'label', 
    'name', 
    'fieldClass' => '', 
    'required' => false, 
    'showHide' => true
])

{{-- If showHide is false, hide the field --}}
@if($showHide)
<div class="form-check mb-3 {{ $fieldClass }}" style="padding-top: 40px; margin-left:10px;">
    <input 
        class="form-check-input" 
        type="checkbox" 
        name="{{ $name }}" 
        id="{{ $name }}" 
        {{ old($name) ? 'checked' : '' }}
        @if($required) required @endif
    >
    <label class="form-check-label" for="{{ $name }}">
        {!! $label !!}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>
</div>
@endif
