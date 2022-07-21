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
        $fotoPerfil = $request->input("fotoPerfil");

        if (is_null($nome) || is_null($email) || is_null($senha) || is_null($hierarquia) || is_null($fotoPerfil)) {
            return response()->json(["Favor informar nome, email, senham, fotoPerfil e a hierarquia do usuario"]);
        }

        $buscaEmail = $this->usuarioFactory->getUsuario(["Email", $email]);
        if ($buscaEmail) {
            return response()->json(["Usuario já existe! Favor tente outro e-mail."]);
        }

        $senha  = password_hash($senha, PASSWORD_ARGON2I);

        if (!$hierarquia === "adm" && !$hierarquia === "com") {
            return response()->json(["Favor informar uma hierarquia válida"]);
        }

        $usuario = new UsuarioModel();
        $usuario->setNome($nome)
            ->setEmail($email)
            ->setSenha($senha)
            ->setHierarquia($hierarquia)
            ->setImagemPerfil($fotoPerfil);


        $retorno = $this->usuarioFactory->createUsuario($usuario);

        if (!$retorno) {
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

        if (!$idDoUsuarioParaExluir) {
            return response()->json(['Favor informar o id do usuário que deseja remover']);
        }

        $retorno = $this->usuarioFactory->removeUsuario($idDoUsuarioParaExluir);

        if ($retorno === 0) {
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

    public function atualizarUsuario(Request $request)
    {
        $cabecalhoHttp = $request->header("Authorization");
        $token = $this->buscarToken($cabecalhoHttp);


        $nome       = $request->input("nome");
        $email      = $request->input("email");
        $senha      = $request->input("senha");
        $hierarquia = $request->input("hierarquia");
        $usuarioId  = $request->input("usuarioId");
        $imagemPerfil  = $request->input("imagemPerfil");


        if (is_null($nome) || is_null($email) || is_null($hierarquia) || is_null($usuarioId) || is_null($imagemPerfil)) {
            return response()->json(["Favor informar nome, email, imagemPerfil, hierarquia e o id do usuario(usuarioId)!"]);
        }

        if (!$hierarquia === "adm" && !$hierarquia === "com") {
            return response()->json(["Favor informar uma hierarquia válida!"]);
        }

        $usuarioAtualizado = new UsuarioModel();
        $usuarioAtualizado->setNome($nome)
            ->setEmail($email)
            ->setHierarquia($hierarquia)
            ->setImagemPerfil($imagemPerfil);

        $retorno = $this->usuarioFactory->updateUsuario($usuarioAtualizado, $usuarioId);

        if (!$retorno) {
            return response()->json("Erro ao atualizar usuário! Tente novamente mais tarde.", 500);
        }

        return response()->json("Usuário atualizado com sucesso!");
    }

    public function atualizarSenha(Request $request)
    {
        $cabecalhoHttp = $request->header("Authorization");
        $token = $this->buscarToken($cabecalhoHttp);

        $senhaAntiga = $request->input("senhaAntiga");
        $novaSenha = $request->input("novaSenha");
        $usuarioId = $request->input("usuarioId");

        if (
            is_null($senhaAntiga) ||
            is_null($novaSenha) ||
            is_null($usuarioId)
        ) {
            return response()->json(["Favor informar senhaAntiga, novaSenha e usarioId"], 403);
        }
        

        $usuarioIdToken = $token->usuario_id;

        if ($usuarioId !== $usuarioIdToken) {
            return response()->json(["Hey hey, não tente fazer gracinhas!"],403);
        }

        $usuario = $this->usuarioFactory->getUsuario(["id", $usuarioId]);

        if (!$usuario) {
            return response()->json(["Usuário não encontrado! Verifique os campos e tente mais tarde."], 500);
        }

        if(!password_verify($senhaAntiga, $usuario->getSenha())){
            return response()->json(["Senha inválida!"], 404);
        }

        $novaSenha = password_hash($novaSenha, PASSWORD_ARGON2I);

        $usuario->setSenha($novaSenha);

        $statusAtualizacao = $this->usuarioFactory->updateUsuarioPassword($usuario, $usuarioId);

        if (!$statusAtualizacao) {
            return response()->json(["Erro ao atualizar usuário! Tente novamente mais tarde"], 500);
        }

        return response()->json(["Senha atualizada com sucesso!"], 200);
    }

    public function buscarDadosUsuario(Request $request)
    {
        $cabecalhoHttp = $request->header("Authorization");
        $token = $this->buscarToken($cabecalhoHttp);
        $usuarioEhAdm = $this->validarAdm($token->usuario_id);

        $usuarioIdToken = $token->usuario_id;
        $usuarioBusca   = $this->usuarioFactory->getUsuario(['id', $usuarioIdToken]);

        if (!$usuarioBusca) {
            return response()->json("Usuario não encontrado, verifique as credenciais e tente novamente.", 500);
        }

        $usuarioRetorno = [
            "id"    => $usuarioBusca->getId(),
            "nome"  => $usuarioBusca->getNome(),
            "email" => $usuarioBusca->getEmail(),
            "hierarquia" => $usuarioBusca->getHierarquia(),
            "Imagem_perfil" => $usuarioBusca->getImagemPerfil(),
        ];

        return response()->json($usuarioRetorno, 200);
    }

    public function verificarHierarquia(Request $request)
    {
        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);

        $resultadoBusca = $this->usuarioFactory->getUsuarioHierarquia($token->usuario_id);
        if (!$resultadoBusca) {
            return response()->json("Usuario não encontrado, verifique as credenciais e tente novamente.", 500);
        }

        return response()->json($resultadoBusca, 200);
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
