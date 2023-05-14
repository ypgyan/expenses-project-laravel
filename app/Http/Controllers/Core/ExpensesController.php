<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Expenses\CreateRequest;
use App\Http\Requests\Core\Expenses\GetExpensesRequest;
use App\Http\Requests\Core\Expenses\UpdateRequest;
use App\Http\Resources\Core\Expenses\ExpenseResource;
use App\Http\Resources\Core\Revenues\RevenueResource;
use App\Services\Expenses\ExpensesService;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    private ExpensesService $expensesService;

    public function __construct(ExpensesService $expensesService)
    {
        $this->expensesService = $expensesService;
    }

    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index(GetExpensesRequest $request): JsonResponse
    {
        try {
            $expenses = $this->expensesService->getAll($request->validated());
            return response()->json(ExpenseResource::collection($expenses));
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function store(CreateRequest $request): JsonResponse
    {
        try {
            $expense = $this->expensesService->createExpense($request->validated());
            return response()->json(new ExpenseResource($expense));
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     * @throws Exception
     */
    public function show(string $id): JsonResponse
    {
        try {
            $expense = $this->expensesService->getExpense($id);
            return response()->json(new ExpenseResource($expense));
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Expense not found');
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        try {
            $expense = $this->expensesService->updateExpense($request->validated(), $id);
            return response()->json(new ExpenseResource($expense));
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Expenses not found');
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws Exception
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->expensesService->removeExpense($id);
            return response()->json(["message" => "Expenses $id deleted"], 200);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Expenses not found');
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function extract(string $year, string $month): JsonResponse
    {
        try {
            $revenues = $this->expensesService->monthExtract($year, $month);
            return response()->json(ExpenseResource::collection($revenues), 200);
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }
}
