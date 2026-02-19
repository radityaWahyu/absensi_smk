<?php

namespace App\Http\Controllers\api;

use App\Exceptions\AuthDeniedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{


    public function __construct(private UserService $user_service) {}

    public function login(LoginRequest $request)
    {

        $form_request = $request->validated();

        $user = $this->user_service->login(
            $form_request['username'],
            $form_request['password'],
            $form_request['device_id'],
            $form_request['device_info']
        );


        return response()->json([
            'success' => true,
            'message' => 'User berhasil login',
            'data' => $user
        ]);
    }

    public function refresh_token(Request $request)
    {

        $token = $request->bearerToken();

        if (!$token) {
            return throw new AuthDeniedException('Header authorization tidak terpasang');
        }

        $response = $this->user_service->refreshToken($token);

        return response()->json([
            'access_token' => $response['access_token'],
            'refresh_token' => $response['refresh_token']
        ], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return throw new AuthDeniedException('Sesi autentikas tidak ditemukan');
        }

        $response = $this->user_service->logout($token);

        if (!$response) {
            return throw new AuthDeniedException('Sesi autentikas tidak ditemukan');
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil logout'
        ]);
    }
}
