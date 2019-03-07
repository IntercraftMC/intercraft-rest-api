<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Attempt to login and return the token on success
     */
    public function login(Request $request)
    {
        $credentials = $request->only(["username", "password"]);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(["error" => "Unauthorized"], 401);
        }
        return response()->json([
            "access_token" => $token,
            "token_type" => "bearer",
            "expires_in" => auth()->factory()->getTTL() * 60 * 24
        ]);
    }

    /**
     * Request a password reset
     */
    public function requestPasswordReset(PasswordResetRequest $request)
    {
        if ($user = User::emailOrUsername($request->email, $request->username)->first()) {
            $token = $user->generatePasswordRequest();
        }
        return response()->json();
    }

    /**
     * Reset a user's password given their request token
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        if ($resetRequest = PasswordReset::activeRequest($request->token)) {
            $user = $resetRequest->user;
            $user->password = Hash::make($request->password);
            $user->save();
            $resetRequest->delete();
            return response()->json([], 200);
        }
        return response()->json([], 422);
    }
}
