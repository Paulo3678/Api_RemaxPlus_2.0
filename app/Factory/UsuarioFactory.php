<?php

namespace App\Factory;

use App\Models\UsuarioModel;
use Illuminate\Support\Facades\DB;

class UsuarioFactory
{
    // ['campo', 'valor']
    public function getUsuario(array $modoBusca)
    {
        try {
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
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function createUsuario(UsuarioModel $usuario): bool
    {
        try {
            $dadosInserios  =   DB::insert("INSERT INTO Usuario(Nome, Email, Senha, Hierarquia, Imagem_perfil) 
                VALUES ('{$usuario->getNome()}', '{$usuario->getEmail()}', '{$usuario->getSenha()}', '{$usuario->getHierarquia()}', '{$usuario->getImagemPerfil()}');
            ");

            if (!$dadosInserios) {
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getUsuarios()
    {
        try {
            $usuarios = DB::select("SELECT * FROM Usuario");
            return $usuarios;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function removeUsuario(int $userId)
    {
        try {
            $usuarioRemovido = DB::delete("DELETE FROM Usuario WHERE id={$userId}");
            return true;
        } catch (\Throwable $e) {
            return false;
        }
        return $usuarioRemovido;
    }

    public function updateUsuario(UsuarioModel $usuario, int $usuarioId)
    {
        try {
            $statusAtualizacao = DB::update("UPDATE Usuario SET 
            nome='{$usuario->getNome()}', email='{$usuario->getEmail()}', senha='{$usuario->getSenha()}', hierarquia='{$usuario->getHierarquia()}' 
            WHERE id='$usuarioId';");
            return true;
        } catch (\Throwable $e) {
            return false;
        }

        return $statusAtualizacao;
    }

    public function getUsuarioHierarquia(int $usuarioId)
    {
        try {
            $resutladoBusca = DB::select("SELECT Hierarquia FROM Usuario WHERE Id={$usuarioId}");
            return $resutladoBusca;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
