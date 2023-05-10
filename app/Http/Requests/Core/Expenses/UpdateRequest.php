<?php

namespace App\Http\Requests\Core\Expenses;

use App\Enums\Expenses\Categories;
use App\Models\Expense;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateRequest extends FormRequest
{
    /**
     * @var true
     */
    private bool $duplicatedDescription = false;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *São produtos que estão bem recentes
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
        $expenseId = $this->route('expense');
        $expense = Expense::find($expenseId);
        $checkDuplicated = true;

        if (!is_null($expense) && $expense->description == $this->description) {
            $checkDuplicated = false;
        }

        if (isset($this->description) && $checkDuplicated && $this->validateDuplicateDescriptionOnMonth($this->description)) {
            $this->duplicatedDescription = true;
        }

        return [
            function (Validator $validator) {
                if ($this->duplicatedDescription) {
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
        $descriptionRepeated = Expense::whereDate('paid_at', '>=', now()->firstOfMonth())
            ->whereDate('paid_at', '<=', now()->lastOfMonth())
            ->where('description', $description)
            ->count();

        if (!is_null($descriptionRepeated) && $descriptionRepeated > 0) {
            return true;
        }
        return false;
    }
}
