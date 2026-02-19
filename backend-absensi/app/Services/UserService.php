<?php

namespace App\Services;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\AuthDeniedException;
use Laravel\Sanctum\PersonalAccessToken;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function login(String $username, String $password, String $phone_id, String $phone_info)
    {
        $user = User::query()
            ->select(
                'id',
                'username',
                'password',
                'phone_id',
                'phone_info',
                'userable_type',
                'userable_id'
            )
            ->with('userable')
            ->where('username', $username)
            ->first();

        $user_detail = [];

        if (! $user || !Hash::check($password, $user->password)) {
            throw new AuthDeniedException('Username dan password tidak terdaftar di sistem.');
        }

        if ($user->$phone_id !== null && $phone_id !== $user->phone_id) {
            throw new AuthDeniedException("Akun anda sudah dipakai di perangkat lain.");
        }

        if (is_null($user->phone_id)) {
            User::where('id', $user->id)
                ->update([
                    'phone_id' => $phone_id,
                    'phone_info' => $phone_info
                ]);
        }


        $access_token = $user->createToken('access_token', ['access'])
            ->plainTextToken;

        $refresh_token = $user->createToken('refresh_token', ['refresh'])
            ->plainTextToken;

        $relation = $user->userable;


        if ($relation instanceof Siswa) {
            $user_detail += [
                'username' => $user->username,
                'nama' => $relation->nama,
                'jenis_kelamin' => $relation->jenis_kelamin,
                'jenis_user' => 'siswa',
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
            ];
        } else {
            $user_detail += [
                'username' => $user->username,
                'nama' => $relation->nama,
                'jenis_kelamin' => $relation->jenis_kelamin,
                'jenis_user' => 'guru',
                'access_token' => $access_token,
                'refresh_token' => $refresh_token,
            ];
        }


        return $user_detail;
    }

    public function refreshToken(String $token)
    {

        $tokenModel = PersonalAccessToken::findToken($token);

        if (!$tokenModel) {
            return throw new AuthDeniedException('Sesion autentikasi tidak ditemukan');
        }

        $user = $tokenModel->tokenable;

        $tokenModel->delete();

        $new_access_token = $user->createToken('access_token', ['access'])
            ->plainTextToken;

        $new_refresh_token = $user->createToken('refresh_token', ['refresh'])
            ->plainTextToken;

        return [
            'access_token' => $new_access_token,
            'refresh_token' => $new_refresh_token
        ];
    }

    public function logout(String $token)
    {
        $tokenModel = PersonalAccessToken::findToken($token);

        if ($tokenModel) {
            $tokenModel->tokenable->tokens()->delete();

            return true;
        }

        return false;
    }
}
