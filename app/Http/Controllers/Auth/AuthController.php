<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\loginRequest;
use App\Http\Resources\JsonWebTokenResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthController extends Controller
{
    public function login(loginRequest $request)
    {

        $credentials = $this->credentials($request);

        if (!Auth::attempt($credentials)) {
            $this->sendFailedLoginResponse($request);
        }

        $user = User::with('permissions')->where('email', $request->email)->first();

        if (!Hash::check($request->password, $user->password, [])) {
            $this->sendFailedLoginResponse($request);
        }

        $tokenResult = $user->createToken('authToken')->plainTextToken;

        return JsonWebTokenResource::make([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'token_type' => 'Bearer',
            'access_token' => $tokenResult,
            'permissions' => $user->permissions,
        ]);
    }

    public function logout(Request $request)
    {
        // Get user who requested the logout
        $user = request()->user();
        // Revoke current user token
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    public function username()
    {
        return 'email';
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $message = trans('auth.failed');
        throw new HttpResponseException(response()->json([
            'message' => 'Authentication failed',
            'errors' => [
                $this->username() => [
                    $message,
                ]
            ],
        ], 422));
    }
}
