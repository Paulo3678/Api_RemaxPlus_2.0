<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use App\Models\UsuarioModel;
use Illuminate\Http\Request;
use App\Factory\UsuarioFactory;

class Usuario extends Controller
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

        if(is_null($email) && is_null($senha)){
            return response()->json(["Necessário passar E-mail e Senha!"], 403);
        }

        $usuario = $this->usuarioFactory->getUsuario(["email", $email]);
        
        if(!$usuario){
            return response()->json("E-mail ou Senha inválidos!", 403);
        }


        var_dump($this->validarUsuario($senha, $usuario));;


        $jwt = JWT::encode(["nome" => "jose"], env('JWT_KEY'), env('JWT_ALG'));

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
