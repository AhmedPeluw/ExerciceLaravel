<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function createUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => 2
        ]);

        $user->assignRole('user');
        return $user;
    }

    public function findByEmail(string $email)
    {
        return User::with("role")->where('email', $email)->first();
    }

    public function getUser()
    {
        return User::with('role')->find(Auth::id());
    }

    public function getAllUsers()
    {

        return User::with("role")->get();
    }

    public function updatePassword(User $user, string $password)
    {
        $user->update(['password' => bcrypt($password)]);
    }
}
