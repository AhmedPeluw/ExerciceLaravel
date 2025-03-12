<?php


namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUser()
    {
        $user = $this->userRepository->getUser();
        $user->role = $user->getRoleNames()->first();

        return $user;
    }

    public function getAllUsers()
    {
        $user = Auth::user();

        $users = $this->userRepository->getAllUsers();

        if ($user->hasRole('admin')) {
            $data = [
                "access" => true,
                "users" => $users
            ];
        } else {
            $data = [
                "access" => false,
                "users" => []
            ];
        }

        return $data;
    }
}
