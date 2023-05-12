<?php

namespace App\Services\Expenses;

use App\Enums\Expenses\Categories;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class ExpensesService
{
    public function createExpense(array $expenseData): Expense
    {
        $expense = new Expense();
        $expense->description = $expenseData['description'];
        $expense->value = $expenseData['value'];
        $expense->category_id = Categories::getCategoryId($expenseData['category'] ?? categories::OUTRAS);
        $expense->paid_at = Carbon::createFromFormat('d-m-Y', $expenseData['paid_at'])->format('Y-m-d');
        $expense->save();
        return $expense;
    }

    public function getAll(array $filters): Collection
    {
        if (empty($filters)) {
            return Expense::all();
        } else {
            return Expense::where('description', 'like', "%{$filters['description']}%")
                ->get();
        }
    }

    public function getExpense(string $id): Expense
    {
        return Expense::findOrFail($id);
    }

    public function removeExpense(string $id): void
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
    }

    public function updateExpense(array $expenseData, string $id): Expense
    {
        $expense = Expense::findOrFail($id);
        $expense->description = $expenseData['description'];
        $expense->value = $expenseData['value'];
        $expense->category_id = Categories::getCategoryId($expenseData['category'] ?? categories::OUTRAS);
        $expense->paid_at = Carbon::createFromFormat('d-m-Y', $expenseData['paid_at'])->format('Y-m-d');
        $expense->save();
        return $expense;
    }
}
