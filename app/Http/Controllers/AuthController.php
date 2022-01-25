<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(LoginRequest $request)
    {
        $token_validity = 24 * 60;

        $this->guard()->factory()->setTTL($token_validity);

        if (!$token = $this->guard()->attempt($request->all())) {
            return response()->json([
                'success' => false,
                'data' => 'Unauthorized'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'token' => $token
        ], JsonResponse::HTTP_OK);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create(array_merge(
            $request->all(),
            ['password' => bcrypt($request->get('password'))]
        ));

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'success' => true,
            'data' => 'Success',
        ]);
    }

    public function profile()
    {
        return response()->json($this->guard()->user());
    }

    public function refresh()
    {
        return response()->json([
            'success' => true,
            'data' => $this->guard()->refresh(),
        ]);
    }

    private function guard()
    {
        return Auth::guard();
    }
}
