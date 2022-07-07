<?php

namespace App\Models;

class UsuarioModel
{
    private $id, $nome, $email, $senha, $hierarquia, $imagemPerfil;

    public function getHierarquia()
    {
        return $this->hierarquia;
    }

    public function setHierarquia($hierarquia)
    {
        $this->hierarquia = $hierarquia;
        return $this;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getImagemPerfil()
    {
        return $this->imagemPerfil;
    }

    public function setImagemPerfil($imagemPerfil)
    {
        $this->imagemPerfil = $imagemPerfil;

        return $this;
    }
}
