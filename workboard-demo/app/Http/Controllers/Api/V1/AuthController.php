<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = $this->service->authenticateApi($credentials['email'], $credentials['password']);

        if (! $user) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 422);
        }

        return response()->json([
            'token' => $this->service->issueToken($user, $credentials['device_name'] ?? 'workboard-api'),
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->service->revokeCurrentToken($request->user());

        return response()->json(status: 204);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()->only('id', 'name', 'email')]);
    }
}
