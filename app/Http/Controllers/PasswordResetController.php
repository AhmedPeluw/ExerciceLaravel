<?php

namespace App\Http\Controllers;

use App\Services\PasswordResetService;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return $this->passwordResetService->sendResetLink($request->email);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        return $this->passwordResetService->resetPassword($request->all());
    }
}
