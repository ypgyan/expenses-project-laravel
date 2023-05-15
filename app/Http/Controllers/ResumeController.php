<?php

namespace App\Http\Controllers;

use App\Services\ResumeService;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    private ResumeService $resumeService;

    public function __construct(ResumeService $resumeService)
    {
        $this->resumeService = $resumeService;
    }

    /**
     * @throws Exception
     */
    public function resume(string $year, string $month): JsonResponse
    {
        try {
            $statistics = $this->resumeService->monthResume($year, $month);
            return response()->json($statistics, 200);
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }
}
