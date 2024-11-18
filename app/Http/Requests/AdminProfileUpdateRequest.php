<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProfileUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
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
            'current_password' => ['required','min:4', 'current_password'],
            'password' => ['required', 'min:4', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Current password is required.',
            'current_password.current_password' => 'Current password is incorrect',
            'current_password.min' => 'Current password is invalid',
            'password.required' => 'New password is required',
        ];
    }
}
