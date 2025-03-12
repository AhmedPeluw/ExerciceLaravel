<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request): JsonResponse
    {
        $user = $this->authService->register($request->all());
        return response()->json(['user' => $user, 'token' => $user->createToken('authToken')->plainTextToken]);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $this->authService->login($request->all());
        return response()->json($data);
    }


    public function user(Request $request): JsonResponse
    {
        $user = $this->authService->getUser();
        return response()->json($user);
    }
}
