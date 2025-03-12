<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function user(Request $request): JsonResponse
    {
        $user = $this->userService->getUser();
        return response()->json($user);
    }

    public function getAllUsers(Request $request): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        return response()->json($users);
    }
}
