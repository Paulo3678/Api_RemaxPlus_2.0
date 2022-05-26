<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use App\Models\UsuarioModel;
use Illuminate\Http\Request;
use App\Factory\UsuarioFactory;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class Login extends Controller
{
    private $usuarioFactory;

    public function __construct() {
        $this->usuarioFactory = new UsuarioFactory();
    }

    // GERADOR DE TOKENS
    public function login(Request $request)
    {
        $email  = $request->input("email");
        $senha  = $request->input("senha");

        if (is_null($email) && is_null($senha)) {
            return response()->json(["Necessário passar E-mail e Senha!"], 403);
        }

        $usuario = $this->usuarioFactory->getUsuario(["email", $email]);

        if (!$usuario) {
            return response()->json("E-mail ou Senha inválidos!", 403);
        }

        $usuarioEhValido = $this->validarUsuario($senha, $usuario);

        if (!$usuarioEhValido) {
            return response()->json("Usuário inválido, e-mail ou senha não correspondem!", 403);
        }

        $jwt = JWT::encode(["usuario_id" => $usuario->getId()], env('JWT_KEY'), env('JWT_ALG'));
        return response()->json($jwt);
    }

    private function validarUsuario($senhaDigitada, UsuarioModel $usuario)
    {
        if(!password_verify($senhaDigitada, $usuario->getSenha())){
            return false;
        }

        return true;
    }
}
