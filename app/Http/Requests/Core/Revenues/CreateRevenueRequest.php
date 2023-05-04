<?php

namespace App\Http\Requests\Core\Revenues;

use App\Models\Revenue;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateRevenueRequest extends FormRequest
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
            'description' => [
                'required',
                'min:3'
            ],
            'value' => [
              'required',
              'gt:0'
            ],
            'received_at' => [
                'required',
                'date_format:d-m-Y'
            ]
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($this->validateDuplicateDescriptionOnMonth($this->description)) {
                    $validator->errors()->add(
                        'description',
                        'Revenue already registered this month!'
                    );
                }
            }
        ];
    }

    private function validateDuplicateDescriptionOnMonth(string $description): bool
    {
        $descriptionRepeated = Revenue::whereDate('received_at' , '>=', now()->firstOfMonth())
            ->whereDate('received_at' , '<=', now()->lastOfMonth())
            ->where('description', $description)
            ->count();

        if ($descriptionRepeated > 0) {
            return true;
        }

        return false;
    }
}
