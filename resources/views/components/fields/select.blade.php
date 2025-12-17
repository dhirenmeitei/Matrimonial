@props([
'label',
'name',
'options' => [],
'readonly' => false,
'fieldClass' => '',
'required' => false,
'showHide' => true,
'value' => null // current value from DB
])
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
        @if($required) required @endif>
        <option value="">-- Select --</option>
        @foreach($options as $optValue => $optText)
        <option value="{{ $optValue }}" {{ old($name, $value) == $optValue ? 'selected' : '' }}>
            {{ $optText }}
        </option>
        @endforeach
    </select>
</div>
@endif