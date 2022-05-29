<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Factory\UsuarioFactory;
use App\Factory\CorretorFactory;
use App\Http\Controllers\Controller;
use App\Models\CorretorModel;
use App\Models\UsuarioModel;

class Corretor extends Controller
{
    private $corretorFactory;
    private $usuarioFactory;

    public function __construct()
    {
        $this->corretorFactory = new CorretorFactory;
        $this->usuarioFactory = new UsuarioFactory();
    }


    public function criarCorretor(Request $request)
    {
        $cabecalho  = $request->header("Authorization");
        $usuarioEhAdm = $this->validarAdm($cabecalho);

        if ($usuarioEhAdm) {
            return response()->json("Opss... Esta página é exclusiva para usuarios, favor deixe que eles resolvam.", 403);
        }
        $dadosToken = $this->buscarToken($cabecalho);

        $nome       = $request->input("nome");
        $email      = $request->input("email");
        $creci      = $request->input("creci");
        $whatsapp   = $request->input("whatsapp");

        if (is_null($nome) || is_null($email) || is_null($creci) || is_null($whatsapp)) {
            return response()->json("Favor informar nome, email, creci, e whatsapp do corretor!");
        }

        $idUsuario  = $dadosToken->usuario_id;
        $corretor = new CorretorModel();
        $corretor->setNome($nome)
            ->setEmail($email)
            ->setCreci($creci)
            ->setWhatsapp($whatsapp)
            ->setIdUsuario($idUsuario);

        $corretorCriado = $this->corretorFactory->createCorretor($corretor);

        if (!$corretorCriado) {
            return response()->json("Erro ao criar corretor, tente novamente mais tarde!", 403);
        }

        return response()->json("Usuario criado com sucesso", 200);
    }

    public function buscarCorretores(Request $request)
    {
        $cabecalho  = $request->header("Authorization");
        $usuarioEhAdm = $this->validarAdm($cabecalho);

        if ($usuarioEhAdm) {
            return response()->json("Opss... Esta página é exclusiva para usuarios, favor deixe que eles resolvam.", 403);
        }
        $dadosToken = $this->buscarToken($cabecalho);

        $idUsuario = $dadosToken->usuario_id;

        $usuarios = $this->corretorFactory->getCorretores($idUsuario);

        if (!$usuarios) {
            return response()->json("Erro ao buscar corretores, tente novamente mais tarde.", 500);
        }

        return response()->json($usuarios, 200);
    }

    public function buscarCorretor(Request $request, int $idCorretorParaBuscar)
    {
        $cabecalho  = $request->header("Authorization");
        $usuarioEhAdm = $this->validarAdm($cabecalho);

        if ($usuarioEhAdm) {
            return response()->json("Opss... Esta página é exclusiva para usuarios, favor deixe que eles resolvam.", 403);
        }
        $dadosToken = $this->buscarToken($cabecalho);

        $idUsuario = $dadosToken->usuario_id;

        $corretor = $this->corretorFactory->getCorretor($idCorretorParaBuscar);
        
        if (!$corretor) {
            return response()->json("Corretor não encontrado.", 404);
        }

        if($idUsuario !== $corretor->getIdUsuario()){
            return response()->json("Esse corretor não pertence a esse usuário", 403);
        }

        return response()->json([
            "id" => $corretor->getId(),
            "usuarioId" => $corretor->getIdUsuario(),
            "nome" => $corretor->getNome(),
            "email" => $corretor->getEmail(),
            "creci" => $corretor->getCreci(),
            "whatsapp" => $corretor->getWhatsapp(),
        ], 200);
    }

    public function excluirCorretor(Request $request)
    {
        $cabecalho      = $request->header("Authorization");
        $usuarioEhAdm   = $this->validarAdm($cabecalho);

        if ($usuarioEhAdm) {
            return response()->json("Opss... Esta página é exclusiva para usuarios, favor deixe que eles resolvam.", 403);
        }

        $corretorParaExcluir = $request->input("corretorId");

        if (is_null($corretorParaExcluir)) {
            return response()->json("Favor, informe o id do corretor que será removido(corretorId)!", 404);
        }

        $resultadoExclusao = $this->corretorFactory->removeCorretor($corretorParaExcluir);

        if (!$resultadoExclusao) {
            return response()->json("Erro ao excluir corretor, tente novamente mais tarde.", 500);
        }

        return response()->json("Corretor excluido com sucesso.", 200);
    }

    public function atualizarCorretor(Request $request)
    {
        $cabecalho      = $request->header("Authorization");
        $usuarioEhAdm   = $this->validarAdm($cabecalho);

        if ($usuarioEhAdm) {
            return response()->json("Opss... Esta página é exclusiva para usuarios, favor deixe que eles resolvam.", 403);
        }

        $corretorId  = $request->input("corretorId");
        $nome       = $request->input("nome");
        $email      = $request->input("email");
        $creci      = $request->input("creci");
        $whatsapp   = $request->input("whatsapp");

        if (is_null($corretorId) || is_null($nome) || is_null($email) || is_null($creci) || is_null($whatsapp)) {
            return response()->json("Favor informar corretorId, nome, email, creci e whatsapp", 403);
        }

        $corretorAtualizado = new CorretorModel();
        $corretorAtualizado->setId($corretorId)
            ->setNome($nome)
            ->setEmail($email)
            ->setCreci($creci)
            ->setWhatsapp($whatsapp);


        $resultadoAtualizacao = $this->corretorFactory->updateCorretor($corretorAtualizado);

        if (!$resultadoAtualizacao) {
            return response()->json("Erro ao atualizar corretor, tente novamente mais tarde", 500);
        }

        return response()->json("Corretor atualizado com sucesso", 200);
    }

    private function buscarToken($cabecalho)
    {
        $token = str_replace("Bearer ", "", $cabecalho);
        $tokenDecodificado = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALG')));
        return $tokenDecodificado;
    }

    private function validarAdm($cabecalho)
    {
        $token = $this->buscarToken($cabecalho);

        if ($token->adm !== "adm") {
            return false;
        }

        return true;
    }
}
