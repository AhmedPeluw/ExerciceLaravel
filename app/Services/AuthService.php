<?php


namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {

        $user = $this->userRepository->createUser($data);

        $user->assignRole('user');

        return $user;
    }

    public function login(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid login credentials.'],
            ]);
        }

        return [
            "user" => $user,
            "token" => $user->createToken('authToken')->plainTextToken
        ];
    }
}
