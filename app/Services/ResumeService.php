<?php

namespace App\Services;

use App\Enums\Expenses\Categories;
use App\Services\Expenses\ExpensesService;
use App\Services\Revenue\RevenuesService;
use Illuminate\Database\Eloquent\Collection;

class ResumeService
{

    private ExpensesService $expensesService;
    private RevenuesService $revenuesService;

    public function __construct(ExpensesService $expensesService, RevenuesService $revenuesService)
    {
        $this->expensesService = $expensesService;
        $this->revenuesService = $revenuesService;
    }

    public function monthResume(string $year, string $month): array
    {
        $resume = [];
        $expensesFromMonth = $this->expensesService->monthExtract($year, $month);
        $revenuesFromMonth = $this->revenuesService->monthExtract($year, $month);

        $resume['totalExpenses'] = $expensesFromMonth->sum('value');
        $resume['totalRevenue'] = $revenuesFromMonth->sum('value');
        $resume['monthBalance'] = round($resume['totalRevenue'] - $resume['totalExpenses'], 2);
        $resume['categoriesResume'] = $this->calculateCategoriesExpenses($expensesFromMonth);

        return $resume;
    }

    private function calculateCategoriesExpenses(Collection $expensesFromMonth): array
    {
        $categories = Categories::getValues();
        $categoriesExpenses = [];
        foreach ($categories as $category) {
            $categoryResume = [
                'name' => $category,
                'value' => $expensesFromMonth->where('category_id', Categories::getCategoryId($category))->sum('value'),
            ];
            $categoriesExpenses[] = $categoryResume;
        }
        return $categoriesExpenses;
    }
}
