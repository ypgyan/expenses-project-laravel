<?php

namespace App\Http\Requests\Core\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min' => 3
            ],
            'email' => [
                'required',
                'email',
                'unique:Users,email'
            ],
            'password' => [
                'required',
            ]
        ];
    }
}
