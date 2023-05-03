<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\CreateRevenueRequest;
use App\Http\Resources\Core\Revenues\RevenueResource;
use App\Services\Revenue\RevenuesService;
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
     */
    public function index()
    {
        //
    }

    public function store(CreateRevenueRequest $request): JsonResponse
    {
        $revenue = $this->revenuesService->createRevenue($request->validated());
        return response()->json(new RevenueResource($revenue));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
