<?php

namespace App\Http\Controllers;

use App\Models\FormFieldMast;
use App\Models\FormMast;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function view($id)
    {
        $user = User::with(['posts.likes', 'posts.comments.user'])
            ->findOrFail($id);

        return view('profile.viewprofile', compact('user'));
    }


    public function edit()
    {
        $form_id = 1; // Use same form definition as registration

        // Load form with active, non-hidden fields
        $form = FormMast::with(['fields' => function ($q) {
            $q->where('status', true)
                ->where('show_hide', true)
                ->whereNotIn('field_name', ['password', 'regis_terms_condition', 'username', 'photo', 'identity_proof']) // ✅ exclude fields
                ->orderBy('field_serial');
        }])
            ->where('form_id', $form_id)
            ->where('status', true)
            ->firstOrFail();

        $user = Auth::user();

        // Prepare select options dynamically
        $optionsData = [];
        foreach ($form->fields as $field) {
            if ($field->field_type === 'select') {
                $tableName = $field->select_from;
                if ($tableName && Schema::hasTable($tableName)) {
                    try {
                        $optionsData[$tableName] = DB::table($tableName)
                            ->pluck('name', 'code')
                            ->toArray();
                    } catch (\Exception $e) {
                        $optionsData[$tableName] = [];
                    }
                } else {
                    $optionsData[$tableName] = [];
                }
            }

            // Get current value from users table using save_to
            $field->current_value = $user->{$field->field_name} ?? null;
        }
        return view('profile.edit', compact('form', 'optionsData', 'user'));
    }

    public function update(Request $request)
    {
        $form_id = 1;

        $form = FormMast::with(['fields' => function ($q) {
            $q->where('status', true)
                ->where('show_hide', true)
                ->whereNotIn('field_name', ['password', 'regis_terms_condition', 'username', 'photo', 'identity_proof']) // ✅ exclude fields
                ->orderBy('field_serial');
        }])
            ->where('form_id', $form_id)
            ->where('status', true)
            ->firstOrFail();

        $user = Auth::user();

        foreach ($form->fields as $field) {
            // Skip readonly fields
            if ($field->readonly) {
                continue;
            }

            $fieldName = $field->field_name;

            if ($field->field_type === 'file_upload' && $request->hasFile($field->field_name)) {
                $file = $request->file($field->field_name);
                $fileData = base64_encode(file_get_contents($file->getRealPath()));
                $user->{$fieldName} = $fileData;
            } else {
                $user->{$fieldName} = $request->input($field->field_name);
            }
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
