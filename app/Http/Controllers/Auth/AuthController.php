<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\UserResource;
use JWTAuth;
use Google2FA;
use App\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'companycode' => 'required|string|max:10|min:1',
            'username' => 'required|string|max:10|min:1',
            'password' => 'required|string|max:32|min:1'
        ]);

        $user = User::where('Sirket_Kod', $request->companycode)->where('SicilNo', $request->username)->where('Sifre', $request->password)->first();
        if (!$user)
            return response()->json(['status' => false, 'message' => 'Geçersiz hesap yada şifre'], 422);

        if (!$user->Aktif)
            return response()->json(['status' => false, 'message' => 'Hesap yönetici tarafından kapatıldı'], 422);

        $token = auth('api')->login($user);

        return response()->json(['status' => true, 'token' => $token]);
    }

    public function refresh(Request $request)
    {
        $current_token = JWTAuth::getToken();
        $token = JWTAuth::refresh($current_token);

        return response()->json(['token' => $token]);
    }

    public function user(Request $request)
    {
        return (new UserResource($request->user()));
    }
}
