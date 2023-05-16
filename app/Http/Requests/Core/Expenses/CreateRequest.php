<?php

namespace App\Http\Requests\Core\Expenses;

use App\Enums\Expenses\Categories;
use App\Models\Expense;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CreateRequest extends FormRequest
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
            'paid_at' => [
                'required',
                'date_format:d-m-Y'
            ],
            'category' => [
                'sometimes',
                Rule::in(Categories::getValues())
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if (isset($this->description) && $this->validateDuplicateDescriptionOnMonth($this->description)) {
                    $validator->errors()->add(
                        'description',
                        'Expense already registered this month!'
                    );
                }
            }
        ];
    }

    private function validateDuplicateDescriptionOnMonth(string $description): bool
    {
        try {
            $verifyDate = Carbon::CreateFromFormat('d-m-Y', $this->paid_at);
            $descriptionRepeated = Expense::whereDate('paid_at', '>=', $verifyDate->firstOfMonth())
                ->whereDate('paid_at', '<=', $verifyDate->lastOfMonth())
                ->where('description', $description)
                ->count();

            if (!is_null($descriptionRepeated) && $descriptionRepeated > 0) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
