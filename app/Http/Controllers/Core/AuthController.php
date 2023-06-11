<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Auth\LoginRequest;
use App\Http\Requests\Core\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $signInResponse = $this->authService->signIn($request->validated());
            return response()->json($signInResponse);
        } catch (ModelNotFoundException|HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->signUp($request->validated());
            return response()->json(new UserResource($user));
        } catch (Exception $e) {
            Bugsnag::notifyException($e);
            throw $e;
        }
    }
}
