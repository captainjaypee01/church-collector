<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomUserStoreCrudRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|unique:'.config('permission.table_names.users', 'users').',email',
            'first_name'     => 'required',
            'last_name'     => 'required',
            'location_id' => 'required',
            'password' => 'required|confirmed',
        ];
    }
}
