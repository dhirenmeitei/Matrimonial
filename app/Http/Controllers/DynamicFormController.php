<?php

namespace App\Http\Controllers;

use App\Models\FormMast;
use App\Models\FormFieldMast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DynamicFormController extends Controller
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
        // return $optionsData;
        return view('forms.dynamic', compact('form', 'optionsData'));
    }

    public function submit(Request $request, $form_id)
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
