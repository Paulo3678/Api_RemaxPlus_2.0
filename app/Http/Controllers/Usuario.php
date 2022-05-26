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

    public function __construct()
    {
        $this->usuarioFactory = new UsuarioFactory();
    }

    public function cadastrarUsuario(Request $request)
    {
        $cabecalhoHttp = $request->header("Authorization");
        $token = $this->buscarToken($cabecalhoHttp);
        $usuarioEhAdm = $this->validarAdm($token->usuario_id);

        if (!$usuarioEhAdm) {
            return response()->json(["Opss... Essa página só é permitida para usuários administradores"], 403);
        }

        $nome       = $request->input("nome");
        $email      = $request->input("email");
        $senha      = $request->input("senha");
        $hierarquia = $request->input("hierarquia");

        if (is_null($nome) || is_null($email) || is_null($senha) || is_null($hierarquia)) {
            return response()->json(["Favor informar nome, email, senha e a hierarquia do usuario"]);
        }

        $senha  = password_hash($senha, PASSWORD_ARGON2I);

        if (!$hierarquia === "adm" && !$hierarquia === "com") {
            return response()->json(["Favor informar uma hierarquia válida"]);
        }

        $usuario = new UsuarioModel();
        $usuario->setNome($nome)
            ->setEmail($email)
            ->setSenha($senha)
            ->setHierarquia($hierarquia);


        $retorno = $this->usuarioFactory->createUsuario($usuario);

        if(!$retorno){
            return response()->json(["Erro ao criar usuário, verifique os campos ou tente mais tarde"], 403);
        }

        return response()->json(['Usuário criado com sucesso!'], 200);

    }

    public function excluirUsuario(Request $request)
    {
        $cabecalhoHttp = $request->header("Authorization");
        $token = $this->buscarToken($cabecalhoHttp);
        $usuarioEhAdm = $this->validarAdm($token->usuario_id);

        if (!$usuarioEhAdm) {
            return response()->json(["Opss... Essa página só é permitida para usuários administradores"], 403);
        }

        $idDoUsuarioParaExluir = $request->input("idParaExcluir");

        if(!$idDoUsuarioParaExluir){
            return response()->json(['Favor informar o id do usuário que deseja remover']);
        }

        $retorno = $this->usuarioFactory->removeUsuario($idDoUsuarioParaExluir);

        if($retorno === 0){
            return response()->json(['Erro ao remover usuario! Verifique se o usuário ainda existe.']);
        }

        return response()->json(["Usuário removido com sucesso!"], 200);

    }

    public function buscarTodosUsuarios(Request $request)
    {
        $cabecalhoHttp = $request->header("Authorization");
        $token = $this->buscarToken($cabecalhoHttp);
        $usuarioEhAdm = $this->validarAdm($token->usuario_id);

        if (!$usuarioEhAdm) {
            return response()->json(["Opss... Essa página só é permitida para usuários administradores"], 403);
        }

        $usuarios = $this->usuarioFactory->getUsuarios();

        return response()->json($usuarios, 200);

    }

    private function buscarToken($cabecalho)
    {
        $token = str_replace("Bearer ", "", $cabecalho);
        $tokenDecodificado = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALG')));
        return $tokenDecodificado;
    }

    private function validarAdm(int $userId)
    {
        $usuario = $this->usuarioFactory->getUsuario(['id', $userId]);
        $hierarquiaUsuario = $usuario->getHierarquia();

        if ($hierarquiaUsuario !== "adm") {
            return false;
        }

        return true;
    }
}
