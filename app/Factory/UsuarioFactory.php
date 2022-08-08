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
                ->setHierarquia($dados[0]->Hierarquia)
                ->setImagemPerfil($dados[0]->Imagem_perfil);

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
            $search = DB::select("SELECT * FROM Usuario");
            $usuarios = [];

            foreach ($search as $usuario) {
                $usuarios[] = [
                    "Id" => $usuario->Id,
                    "Nome" => $usuario->Nome,
                    "Email" => $usuario->Email,
                    "Hierarquia" => $usuario->Hierarquia,
                    "Imagem_perfil" => $usuario->Imagem_perfil
                ];
            }
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
            nome='{$usuario->getNome()}', email='{$usuario->getEmail()}', hierarquia='{$usuario->getHierarquia()}', Imagem_perfil='{$usuario->getImagemPerfil()}' 
            WHERE Id='$usuarioId';");
            return true;
        } catch (\Throwable $e) {
            return false;
        }

        return $statusAtualizacao;
    }

    public function updateUsuarioPassword(UsuarioModel $usuario, int $usuarioId)
    {
        try {
            $statusAtualizacao = DB::update("UPDATE Usuario SET 
            nome='{$usuario->getNome()}', email='{$usuario->getEmail()}', hierarquia='{$usuario->getHierarquia()}', Imagem_perfil='{$usuario->getImagemPerfil()}', Senha='{$usuario->getSenha()}' 
            WHERE Id='$usuarioId';");
            return true;
        } catch (\Throwable $e) {
            // return false;
            echo $e->getMessage();
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
