<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Revenues\CreateRevenueRequest;
use App\Http\Resources\Core\Revenues\RevenueResource;
use App\Models\Revenue;
use App\Services\Revenue\RevenuesService;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RevenuesController extends Controller
{
    private RevenuesService $revenuesService;

    public function __construct(RevenuesService $revenuesService)
    {
        $this->revenuesService = $revenuesService;
    }

    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        try {
            $revenues = $this->revenuesService->getAll();
            return response()->json(RevenueResource::collection($revenues));
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function store(CreateRevenueRequest $request): JsonResponse
    {
        try {
            $revenue = $this->revenuesService->createRevenue($request->validated());
            return response()->json(new RevenueResource($revenue));
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(501, 'Resource not implemented');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(501, 'Resource not implemented');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(501, 'Resource not implemented');
    }
}
