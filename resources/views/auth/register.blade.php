@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div class="container-fluid p-5">
    <div class="row justify-content-center">
        <div class="col">

            {{-- Session Alerts --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form Box (same as Contact form) -->
            <div class="bg-light rounded p-5 wow fadeIn" data-wow-delay="0.1s">

                <h5 class="section-title text-center">Register To</h5>
                <h1 class="display-6 text-center mb-4">
                    <!-- {{ $form->form_title }} --> SOULMADE
                </h1>
                <hr>
                <br>

                <form method="POST"
                    action="{{ route('form.submit', $form->form_id) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">

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
                            :fieldClass="$field->field_class.' border-0 px-4'"
                            :readonly="$field->readonly"
                            :required="$isRequired"
                            :showHide="$showHide"
                            :value="$oldValue" />
                        @break

                        {{-- Text Area --}}
                        @case('text_area')
                        <x-fields.text-area
                            :label="$field->label_name"
                            :name="$field->field_name"
                            :fieldClass="$field->field_class.' border-0 px-4'"
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
                            :fieldClass="$field->field_class.' border-0 px-4'"
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
                            :fieldClass="$field->field_class.' border-0 px-4'"
                            :required="$isRequired"
                            :showHide="$showHide"
                            :value="$oldValue" />
                        @break

                        {{-- Password --}}
                        @case('password')
                        <x-fields.text-password
                            :label="$field->label_name"
                            :name="$field->field_name"
                            :fieldClass="$field->field_class.' border-0 px-4'"
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
                            :fieldClass="$field->field_class.' border-0 px-4'"
                            :options="$options"
                            :required="$isRequired"
                            :selected="$oldValue" />
                        @break

                        {{-- Default --}}
                        @default
                        <x-fields.text-input
                            :label="$field->label_name"
                            :name="$field->field_name"
                            :fieldClass="$field->field_class.' border-0 px-4'"
                            :value="$oldValue" />

                        @endswitch
                        @endif
                        @endforeach

                    </div>
                    <hr>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-3">
                            Submit
                        </button>
                    </div>


                </form>
            </div>

        </div>
    </div>
</div>

@endsection