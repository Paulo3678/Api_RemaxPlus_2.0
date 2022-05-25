<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
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

        $usuarioEhValido = $this->validarUsuario($senha, $usuario);

        if(!$usuarioEhValido){
            return response()->json("Usuário inválido, e-mail ou senha não correspondem!", 403);          
        }

        $jwt = JWT::encode(["usuario_id" => $usuario->getId()], env('JWT_KEY'), env('JWT_ALG'));
        
        return response()->json($jwt);
    }

    public function cadastrarUsuario(Request $request)
    {
        $cabecalhoHttp = $request->header("Authorization");        
        $token = $this->buscarToken($cabecalhoHttp);
        $usuarioEhAdm = $this->validarAdm($token->usuario_id);

        if(!$usuarioEhAdm){
            return response()->json(["Opss... Essa página só é permitida para usuários administradores"], 403);
        }

        $nome       = $request->input("nome");
        $email      = $request->input("email");
        $senha      = $request->input("senha");
        $hierarquia = $request->input("hierarquia");

        if(is_null($nome) || is_null($email) ||is_null($senha) ||is_null($hierarquia)){
            return response()->json(["Favor informar nome, email, senha e a hierarquia do usuario"]);
        }

        $senha  = password_hash($senha, env('PASS_HASH'));

        if($hierarquia !== "adm" || $hierarquia !== "com"){
            return response()->json(["Favor informar uma hierarquia"]);
        }

        $usuario = new UsuarioModel();
        $usuario->setNome($nome)
            ->setEmail($email)
            ->setSenha($senha)
            ->setHierarquia($hierarquia);

        


    }


    private function validarUsuario($senhaDigitada, UsuarioModel $usuario)
    {
        if(!password_verify($senhaDigitada, $usuario->getSenha())){
            return false;
        }

        return true;
    }

    private function buscarToken($cabecalho){
        $token = str_replace("Bearer ", "", $cabecalho);
        $tokenDecodificado = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALG')));
        return $tokenDecodificado;
    }

    private function validarAdm(int $userId){
        $usuario = $this->usuarioFactory->getUsuario(['id', $userId]);
        $hierarquiaUsuario = $usuario->getHierarquia();

        if($hierarquiaUsuario !== "adm"){
            return false;
        }

        return true;

    }
}
