{{-- text-area.blade.php --}}
@props([
    'label',
    'name',
    'readonly' => false,
    'fieldClass' => '',
    'required' => false,
    'showHide' => true,
    'rows' => 3,
    'maxWords' => 50,
    'value' => null // ✅ current value from DB
])

@if($showHide)
<div class="mb-3 {{ $fieldClass }}">
    <label class="form-label fw-semibold">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
        <small class="text-muted">(Max {{ $maxWords }} words)</small>
    </label>

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        class="form-control"
        rows="{{ $rows }}"
        oninput="limitWords(this, {{ $maxWords }})"
        @if($readonly) readonly @endif
        @if($required) required @endif>{{ old($name, $value) }}</textarea>

    <small class="text-muted">
        <span id="{{ $name }}_count">0</span> / {{ $maxWords }} words
    </small>
</div>
@endif

<script>
function limitWords(textarea, maxWords) {
    let words = textarea.value.trim().split(/\s+/).filter(w => w.length);
    let counter = document.getElementById(textarea.id + '_count');

    if (words.length > maxWords) {
        textarea.value = words.slice(0, maxWords).join(' ');
        words = words.slice(0, maxWords);
    }

    if (counter) {
        counter.innerText = words.length;
    }
}
</script>
