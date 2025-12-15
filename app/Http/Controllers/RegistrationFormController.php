<?php

namespace App\Http\Controllers;

use App\Models\FormMast;
use App\Models\FormFieldMast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegistrationFormController extends Controller
{
    // public function show($form_id)
    public function show()
    {
        $form_id = 1;

        $form = FormMast::with(['fields'])
            ->where('form_id', $form_id)
            ->where('status', true)
            ->firstOrFail();

        // Prepare options array keyed by field_name
        $optionsData = [];
        foreach ($form->fields as $field) {
            if ($field->field_type === 'select') {
                $tableName = $field->select_from; // table name same as field_name

                if (Schema::hasTable($tableName)) {
                    try {
                        // Fetch "code" and "name" from table
                        $optionsData[$tableName] = DB::table($tableName)
                            ->pluck('name', 'code')
                            ->toArray();
                    } catch (\Exception $e) {
                        // in case table doesn’t have expected columns
                        $optionsData[$tableName] = [];
                    }
                } else {
                    $optionsData[$tableName] = [];
                }
            }
        }
        // return $form;
        return view('auth.register', compact('form', 'optionsData'));
    }

    public function submit(Request $request, $form_id)
    {
        $fields = FormFieldMast::where('form_id', $form_id)
            ->where('status', true)
            ->orderBy('field_serial')
            ->get();

        /* ------------------------------------
    | 1. BUILD VALIDATION RULES DYNAMICALLY
    ------------------------------------ */
        $rules = [];
        $messages = [];

        foreach ($fields as $field) {

            $name = $field->field_name;

            // Skip auto-generated fields
            if ($name === 'p_id') {
                continue;
            }

            $rule = [];

            /* Required check */
            if ($field->required) {
                $rule[] = 'required';
                $messages["$name.required"] = "{$field->label_name} is required.";
            } else {
                $rule[] = 'nullable';
            }

            /* Field type based rules */
            switch ($field->field_type) {

                case 'text_input':
                    $rule[] = 'string';
                    $rule[] = 'max:255';
                    break;

                case 'number_input':
                    $rule[] = 'numeric';
                    break;

                case 'date':
                    $rule[] = 'date';
                    break;

                case 'password':
                    $rule[] = 'string';
                    $rule[] = 'min:8';
                    break;

                case 'file_upload':
                    $rule[] = 'file';
                    $rule[] = 'mimes:jpg,jpeg,png';
                    $rule[] = 'max:2048'; // 2MB
                    $messages["$name.mimes"] = "Only JPG or PNG files are allowed.";
                    break;

                case 'check_box':
                    $rule[] = 'accepted';
                    break;

                case 'select':
                    $rule[] = 'string';
                    break;
            }

            $rules[$name] = implode('|', $rule);
        }

        /* ------------------------------------
    | 2. VALIDATE REQUEST
    ------------------------------------ */
        Validator::make($request->all(), $rules, $messages)->validate();

        /* ------------------------------------
    | 3. SAVE DATA (TABLE-WISE)
    ------------------------------------ */
        $groupedFields = $fields->groupBy('save_to');

        foreach ($groupedFields as $table => $tableFields) {

            if (!$table) continue;

            $data = [];

            foreach ($tableFields as $field) {

                $fieldName = $field->field_name;

                /* Auto-generate p_id */
                if ($fieldName === 'p_id') {
                    do {
                        $uniqueKey = strtoupper(Str::random(6));
                    } while (DB::table($table)->where('p_id', $uniqueKey)->exists());

                    $data[$fieldName] = $uniqueKey;
                }

                /* Password encryption */ elseif ($fieldName === 'password' && $request->filled('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                /* File upload as Base64 */ elseif ($field->field_type === 'file_upload') {

                    if ($request->hasFile($fieldName)) {
                        $file = $request->file($fieldName);

                        if ($file->isValid()) {
                            $fileContent = file_get_contents($file->getRealPath());
                            $base64 = base64_encode($fileContent);
                            $mime = $file->getMimeType(); // e.g., image/jpeg
                            $data[$fieldName] = "data:$mime;base64,$base64";
                        }
                    } else {
                        // Mandatory file validation
                        if ($field->required) {
                            return back()->withErrors(["$fieldName" => "{$field->label_name} is required."])->withInput();
                        }
                    }
                }

                /* Normal fields */ elseif ($request->has($fieldName)) {
                    $data[$fieldName] = $request->input($fieldName);
                }
            }

            if (!empty($data)) {
                DB::table($table)->insert($data);
            }
        }

        return back()->with('success', 'Form submitted successfully!');
    }

    public function submit_backup_b4_b64_photo_save(Request $request, $form_id)
    {
        $fields = FormFieldMast::where('form_id', $form_id)
            ->where('status', true)
            ->orderBy('field_serial')
            ->get();

        /* ------------------------------------
     | 1. BUILD VALIDATION RULES DYNAMICALLY
     ------------------------------------ */
        $rules = [];
        $messages = [];

        foreach ($fields as $field) {

            $name = $field->field_name;

            // Skip auto-generated fields
            if ($name === 'p_id') {
                continue;
            }

            $rule = [];

            /* Required check */
            if ($field->required) {
                $rule[] = 'required';
                $messages["$name.required"] = "{$field->label_name} is required.";
            } else {
                $rule[] = 'nullable';
            }

            /* Field type based rules */
            switch ($field->field_type) {

                case 'text_input':
                    $rule[] = 'string';
                    $rule[] = 'max:255';
                    break;

                case 'number_input':
                    $rule[] = 'numeric';
                    break;

                case 'date':
                    $rule[] = 'date';
                    break;

                case 'password':
                    $rule[] = 'string';
                    $rule[] = 'min:8';
                    break;

                case 'file_upload':
                    $rule[] = 'file';
                    $rule[] = 'mimes:jpg,jpeg,png';
                    $rule[] = 'max:2048'; // 2MB
                    $messages["$name.mimes"] = "Only JPG or PNG files are allowed.";
                    break;

                case 'check_box':
                    $rule[] = 'accepted';
                    break;

                case 'select':
                    $rule[] = 'string';
                    break;
            }

            $rules[$name] = implode('|', $rule);
        }

        /* ------------------------------------
     | 2. VALIDATE REQUEST
     ------------------------------------ */
        Validator::make($request->all(), $rules, $messages)->validate();

        /* ------------------------------------
     | 3. SAVE DATA (TABLE-WISE)
     ------------------------------------ */
        $groupedFields = $fields->groupBy('save_to');

        foreach ($groupedFields as $table => $tableFields) {

            if (!$table) continue;

            $data = [];

            foreach ($tableFields as $field) {

                $fieldName = $field->field_name;

                /* Auto-generate p_id */
                if ($fieldName === 'p_id') {
                    do {
                        $uniqueKey = strtoupper(Str::random(6));
                    } while (DB::table($table)->where('p_id', $uniqueKey)->exists());

                    $data[$fieldName] = $uniqueKey;
                }

                /* Password encryption */ elseif ($fieldName === 'password' && $request->filled('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                /* File upload */ elseif ($field->field_type === 'file_upload' && $request->hasFile($fieldName)) {

                    $file = $request->file($fieldName);
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('uploads', $filename, 'public');

                    $data[$fieldName] = $path;
                }

                /* Normal fields */ elseif ($request->has($fieldName)) {
                    $data[$fieldName] = $request->input($fieldName);
                }
            }

            if (!empty($data)) {
                DB::table($table)->insert($data);
            }
        }

        return back()->with('success', 'Form submitted successfully!');
    }

    // ..............backup........................
    public function submit_backup_b4_validation(Request $request, $form_id)
    {
        $fields = FormFieldMast::where('form_id', $form_id)->get();
        $groupedFields = $fields->groupBy('save_to');

        foreach ($groupedFields as $table => $tableFields) {

            $data = [];

            foreach ($tableFields as $field) {
                $fieldName = $field->field_name;

                // Auto-generate p_id
                if ($fieldName === 'p_id') {
                    $uniqueKey = strtoupper(Str::random(6));

                    while (DB::table($table)->where('p_id', $uniqueKey)->exists()) {
                        $uniqueKey = strtoupper(Str::random(6));
                    }

                    $data[$fieldName] = $uniqueKey;
                }

                // Encrypt password before saving
                elseif ($fieldName === 'password' && $request->has('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                // Normal fields
                elseif ($request->has($fieldName)) {
                    $data[$fieldName] = $request->input($fieldName);
                }
            }
            if (!empty($data) && $table) {
                DB::table($table)->insert($data);
            }
        }

        return back()->with('success', 'Form submitted successfully!');
    }

    public function submit_backup(Request $request, $form_id)
    {
        // 1. Fetch all fields for the given form
        $fields = FormFieldMast::where('form_id', $form_id)->get();

        // 2. Group fields by their save_to table name
        $groupedFields = $fields->groupBy('save_to');

        // 3. Loop through each table group
        foreach ($groupedFields as $table => $tableFields) {

            $data = [];

            foreach ($tableFields as $field) {
                $fieldName = $field->field_name;

                // ✅ If field_name is 'p_id', generate unique 6-character alphanumeric key
                if ($fieldName === 'p_id') {
                    $uniqueKey = strtoupper(Str::random(6)); // e.g. 'A9B3XQ'

                    // Optional: ensure it's unique in this table
                    while (DB::table($table)->where('p_id', $uniqueKey)->exists()) {
                        $uniqueKey = strtoupper(Str::random(6));
                    }

                    $data[$fieldName] = $uniqueKey;
                }
                // ✅ Otherwise, take from request
                elseif ($request->has($fieldName)) {
                    $data[$fieldName] = $request->input($fieldName);
                }
            }

            // 4. Insert data into the respective table (if not empty)
            if (!empty($data) && $table) {
                DB::table($table)->insert($data);
            }
        }

        return back()->with('success', 'Form submitted successfully!');
    }



    // public function show()
    // {
    //     $form_id = 1;
    //     $form = FormMast::with(['fields'])
    //         ->where('form_id', $form_id)
    //         ->where('status', true)
    //         ->firstOrFail();

    //     $genders = [
    //         'M' => 'Male',
    //         'F' => 'Female',
    //         'O' => 'Other',
    //     ];

    //     return view('forms.dynamic', compact('form', 'genders'));
    // }

    // public function submit_old(Request $request, $form_id)
    // {
    //     // 1. Fetch all fields for the given form
    //     $fields = FormFieldMast::where('form_id', $form_id)->get();

    //     // 2. Group fields by their save_to table name
    //     $groupedFields = $fields->groupBy('save_to');

    //     // 3. Loop through each table group
    //     foreach ($groupedFields as $table => $tableFields) {

    //         // Build the data array for that table
    //         $data = [];

    //         foreach ($tableFields as $field) {
    //             $fieldName = $field->field_name;
    //             if ($request->has($fieldName)) {
    //                 $data[$fieldName] = $request->input($fieldName);
    //             }
    //         }

    //         // 4. Insert data into the respective table (if not empty)
    //         if (!empty($data) && $table) {
    //             DB::table($table)->insert($data);
    //         }
    //     }

    //     return back()->with('success', 'Form submitted successfully!');
    // }

}
