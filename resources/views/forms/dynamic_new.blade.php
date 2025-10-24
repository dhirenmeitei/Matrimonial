<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $form->form_title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light py-4">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h3 class="text-center mb-4">{{ $form->form_title }}</h3>

        <form method="POST" action="{{ route('form.submit', $form->form_id) }}" enctype="multipart/form-data">
            @csrf
            <div class="card shadow-sm p-4">
                <div class="row">

                    @foreach ($form->fields as $field)
                        @if ($field->status)
                            @php
                                $type = strtolower($field->field_type);
                                $field_name = strtolower($field->field_name);

                                // Determine dynamic visibility and required status
                                $isHidden = isset($field->show_hide) && $field->show_hide === 'hide';
                                $isRequired = isset($field->required) && (int) $field->required === 1;
                            @endphp

                            {{-- Keep your original structure and styling --}}
                            <div class="{{ $field->field_class }}" id="field_{{ $field_name }}" style="{{ $isHidden ? 'display:none;' : '' }}">
                                @switch($type)
                                    @case('text_input')
                                        <x-fields.text-input
                                            :label="$field->label_name"
                                            :name="$field->field_name"
                                            :fieldClass="$field->field_class"
                                            :readonly="$field->readonly"
                                            :required="$isRequired" />
                                        @break

                                    @case('number_input')
                                        <x-fields.number-input
                                            :label="$field->label_name"
                                            :name="$field->field_name"
                                            :fieldClass="$field->field_class"
                                            :readonly="$field->readonly"
                                            :required="$isRequired" />
                                        @break

                                    @case('date')
                                        <x-fields.date-input
                                            :label="$field->label_name"
                                            :name="$field->field_name"
                                            :fieldClass="$field->field_class"
                                            :required="$isRequired" />
                                        @break

                                    @case('check_box')
                                        <x-fields.checkbox
                                            :label="$field->label_name"
                                            :name="$field->field_name"
                                            :fieldClass="$field->field_class"
                                            :required="$isRequired" />
                                        @break

                                    @case('file_upload')
                                        <x-fields.file-upload
                                            :label="$field->label_name"
                                            :name="$field->field_name"
                                            :fieldClass="$field->field_class"
                                            :required="$isRequired" />
                                        @break

                                    @case('password')
                                        <x-fields.text-password
                                            :label="$field->label_name"
                                            :name="$field->field_name"
                                            :fieldClass="$field->field_class"
                                            :required="$isRequired" />
                                        @break

                                    @case('select')
                                        @php
                                            $options = $optionsData[$field->select_from] ?? [];
                                        @endphp
                                        <x-fields.select
                                            :label="$field->label_name"
                                            :name="$field->field_name"
                                            :fieldClass="$field->field_class"
                                            :options="$options"
                                            :required="$isRequired" />
                                        @break

                                    @default
                                        <x-fields.text-input
                                            :label="$field->label_name"
                                            :name="$field->field_name"
                                            :fieldClass="$field->field_class"
                                            :required="$isRequired" />
                                @endswitch
                            </div>
                        @endif
                    @endforeach

                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
