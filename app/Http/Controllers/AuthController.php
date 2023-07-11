<?php

namespace App\Http\Controllers;

use App\Traits\Response;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use Response;

    public function login(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->error('Unauthorized');
            }
            return $this->success(['token' => $token], 'Berhasil Login');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->error($th->getMessage(), $th->getCode());
        }
    }

    public function logout()
    {
        try {
            auth()->logout();
            return $this->success(null, 'Berhasil Logout');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->error($th->getMessage(), $th->getCode());
        }
    }

    public function me()
    {
        try {
            $user = auth()->user();
            return $this->success($user, 'Berhasil Mendapatkan Data User');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->error($th->getMessage(), $th->getCode());
        }
    }
}
