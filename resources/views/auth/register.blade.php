@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container">

    {{-- Session Alerts --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Centered Card --}}
    <div class="row justify-content-center mt-5">
        <div class="col">

            <div class="card shadow border-0">
                <div class="card-header text-center">
                    <h4 class="mb-0">{{ $form->form_title }}</h4>
                </div>

                <div class="card-body p-4">

                    <form method="POST"
                        action="{{ route('form.submit', $form->form_id) }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            @foreach ($form->fields as $field)
                            @if ($field->status)

                            @php
                            $type = strtolower($field->field_type);
                            $isRequired = $field->required ?? false;
                            $showHide = $field->show_hide ?? true;
                            $oldValue = old($field->field_name);
                            @endphp

                            @switch($type)

                            {{-- Text Input --}}
                            @case('text_input')
                            <x-fields.text-input
                                :label="$field->label_name"
                                :name="$field->field_name"
                                :fieldClass="$field->field_class"
                                :readonly="$field->readonly"
                                :required="$isRequired"
                                :showHide="$showHide"
                                :value="$oldValue" />
                            @break

                            {{-- Number Input --}}
                            @case('number_input')
                            <x-fields.number-input
                                :label="$field->label_name"
                                :name="$field->field_name"
                                :fieldClass="$field->field_class"
                                :readonly="$field->readonly"
                                :required="$isRequired"
                                :showHide="$showHide"
                                :value="$oldValue" />
                            @break

                            {{-- Date Input --}}
                            @case('date')
                            <x-fields.date-input
                                :label="$field->label_name"
                                :name="$field->field_name"
                                :fieldClass="$field->field_class"
                                :required="$isRequired"
                                :showHide="$showHide"
                                :value="$oldValue" />
                            @break

                            {{-- Password --}}
                            @case('password')
                            <x-fields.text-password
                                :label="$field->label_name"
                                :name="$field->field_name"
                                :fieldClass="$field->field_class"
                                :required="$isRequired"
                                :showHide="$showHide" />
                            @break

                            {{-- Checkbox --}}
                            @case('check_box')
                            <x-fields.checkbox
                                :label="$field->label_name"
                                :name="$field->field_name"
                                :fieldClass="$field->field_class"
                                :required="$isRequired"
                                :showHide="$showHide"
                                :checked="$oldValue" />
                            @break

                            {{-- File Upload --}}
                            @case('file_upload')
                            <x-fields.file-upload
                                :label="$field->label_name"
                                :name="$field->field_name"
                                :fieldClass="$field->field_class"
                                :required="$isRequired"
                                :showHide="$showHide" />
                            @break

                            {{-- Select --}}
                            @case('select')
                            @php
                            $options = $optionsData[$field->select_from] ?? [];
                            @endphp
                            <x-fields.select
                                :label="$field->label_name"
                                :name="$field->field_name"
                                :fieldClass="$field->field_class"
                                :options="$options"
                                :required="$isRequired"
                                :selected="$oldValue" />
                            @break

                            {{-- Default --}}
                            @default
                            <x-fields.text-input
                                :label="$field->label_name"
                                :name="$field->field_name"
                                :fieldClass="$field->field_class"
                                :value="$oldValue" />
                            @endswitch

                            @endif
                            @endforeach
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                Submit
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection