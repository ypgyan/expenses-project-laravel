<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Revenues\CreateRequest;
use App\Http\Requests\Core\Revenues\UpdateRequest;
use App\Http\Resources\Core\Expenses\ExpenseResource;
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
    public function index(): JsonResponse
    {
        try {
            $revenues = $this->expensesService->getAll();
            return response()->json(ExpenseResource::collection($revenues));
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
            $revenue = $this->expensesService->createExpense($request->validated());
            return response()->json(new ExpenseResource($revenue));
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
            $revenue = $this->expensesService->getExpense($id);
            return response()->json(new ExpenseResource($revenue));
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Revenue not found');
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
            $revenue = $this->expensesService->updateExpense($request->validated(), $id);
            return response()->json(new ExpenseResource($revenue));
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Revenue not found');
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
            return response()->json(["message" => "Revenue $id deleted"], 200);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Revenue not found');
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }
}
