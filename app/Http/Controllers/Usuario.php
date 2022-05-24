<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;

class Usuario extends Controller
{

    // GERADOR DE TOKENS
    public function login()
    {
        $jwt = JWT::encode(["nome" => "jose"], env('JWT_KEY'), env('JWT_ALG'));

        return response()->json($jwt);
    }
}
