@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div class="container-fluid p-5">
    <div class="row justify-content-center">
        <div class="col">

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

            <div class="bg-light rounded p-5">

                <h1 class="text-center mb-4">Edit Profile</h1>
                <hr>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        @foreach ($form->fields as $field)
                        {{-- Only show active, non-hidden fields --}}
                        @if ($field->status && $field->show_hide)

                        @php
                        $type = strtolower($field->field_type);
                        $isRequired = $field->required ?? false;
                        $readonly = $field->readonly ?? false;
                        $currentValue = $field->current_value ?? null;
                        @endphp

                        @switch($type)

                        {{-- Text Input --}}
                        @case('text_input')
                        <x-fields.text-input
                            :label="$field->label_name"
                            :name="$field->field_name"
                            :fieldClass="$field->field_class.' border-0 px-4'"
                            :readonly="$readonly"
                            :required="$isRequired"
                            :value="$currentValue" />
                        @break

                        {{-- Text Area --}}
                        @case('text_area')
                        <x-fields.text-area
                            :label="$field->label_name"
                            :name="$field->field_name"
                            :fieldClass="$field->field_class.' border-0 px-4'"
                            :readonly="$readonly"
                            :required="$isRequired"
                            :value="$currentValue" />
                        @break

                        {{-- File Upload --}}
                        @case('file_upload')
                        <div class="col-6">
                            <label class="form-label">{{ $field->label_name }}</label>
                            @if($currentValue)
                            <div class="mb-2">
                                <img src="data:image/png;base64,{{ $currentValue }}" class="img-fluid rounded" style="max-height:120px">
                            </div>
                            @endif
                            @if(!$readonly)
                            <input type="file" name="{{ $field->field_name }}" class="form-control">
                            @endif
                        </div>
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
                            :value="$currentValue"
                            :readonly="$readonly" />
                        @break

                        {{-- Default --}}
                        @default
                        <x-fields.text-input
                            :label="$field->label_name"
                            :name="$field->field_name"
                            :fieldClass="$field->field_class.' border-0 px-4'"
                            :readonly="$readonly"
                            :required="$isRequired"
                            :value="$currentValue" />
                        @endswitch

                        @endif
                        @endforeach

                    </div>

                    <hr>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-sm btn-primary px-5 py-3">Update Profile</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection