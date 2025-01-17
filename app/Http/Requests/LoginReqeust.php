<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginReqeust extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', Rule::exists('users', 'username')],
            'password' => ['required', 'min:4']
        ];
    }

    public function messages(): array
    {
        return [
            'username.exists' => 'Invalid username',
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
            'password.min' => 'Invalid password'
        ];
    }
}
