<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */

    public function rules()
    {
        return [
            'username' => 'required|unique:users,username',
            'phone_number' => 'required|unique:users,phone_number'
        ];
    }

    public function messages()
    {
        return [
            'username.reuired' => 'Username required.',
            'phone_number.required' => 'Phone number required.'
        ];
    }
}
