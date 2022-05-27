<?php

namespace App\Factory;

use App\Models\UsuarioModel;
use Illuminate\Support\Facades\DB;

class UsuarioFactory
{
    // ['campo', 'valor']
    public function getUsuario(array $modoBusca)
    {
        $dados = null;

        if (is_string($modoBusca[1])) {
            $dados = DB::select("SELECT * FROM Usuario WHERE {$modoBusca[0]}='{$modoBusca[1]}'");
        } else {
            $dados = DB::select("SELECT * FROM Usuario WHERE {$modoBusca[0]}={$modoBusca[1]}");
        }

        if (count($dados) === 0) {
            return false;
        }

        $usuario = new UsuarioModel();
        $usuario->setId($dados[0]->Id)
            ->setNome($dados[0]->Nome)
            ->setSenha($dados[0]->Senha)
            ->setEmail($dados[0]->Email)
            ->setHierarquia($dados[0]->Hierarquia);

        return $usuario;
    }

    public function createUsuario(UsuarioModel $usuario): bool
    {
        $dadosInserios  =   DB::insert("INSERT INTO Usuario(Nome, Email, Senha, Hierarquia) 
            VALUES ('{$usuario->getNome()}', '{$usuario->getEmail()}', '{$usuario->getSenha()}', '{$usuario->getHierarquia()}');
        ");

        if (!$dadosInserios) {
            return false;
        }
        return true;
    }

    public function getUsuarios()
    {
        $usuarios = DB::select("SELECT * FROM Usuario");
        return $usuarios;
    }

    public function removeUsuario(int $userId)
    {
        $usuarioRemovido = DB::delete("DELETE FROM Usuario WHERE id={$userId}");
        return $usuarioRemovido;
    }

    public function updateUsuario(UsuarioModel $usuario, int $usuarioId)
    {
        $statusAtualizacao = DB::update("UPDATE Usuario SET 
            nome='{$usuario->getNome()}', email='{$usuario->getEmail()}', senha='{$usuario->getSenha()}', hierarquia='{$usuario->getHierarquia()}' 
        WHERE id='$usuarioId';");

        return $statusAtualizacao;
    }
}
