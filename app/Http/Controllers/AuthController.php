<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credenciais = $request->all(['email', 'password']);

        $token = auth('api')->attempt($credenciais);

        if ($token) { // usuario autenticado com sucesso
            return response()->json(['token' => $token]);
        } else { // erro ao autenticar o usuario
            return response()->json(['erro' => 'Usuário ou senha incorretos'], 403);
        }

        return 'login';
    }

    public function logout()
    {
        \auth()->logout();
        return \response()->json(['msg' => 'Logout efetuado com sucesso!']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();

        return \response()->json(['token' => $token]);
    }

    public function me()
    {
        return \response()->json(auth()->user());
    }
}