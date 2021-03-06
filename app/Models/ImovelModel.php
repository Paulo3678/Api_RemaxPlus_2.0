<?php

namespace App\Models;

class ImovelModel
{
    private $id, $corretorId, $titulo, $tituloSlug, $imagemCapa,
        $descricao, $situacao, $tamanho, $usuarioId,
        $preco, $numeroQuartos, $numeroBanheiros, $numeroVagas, 
        $numerosSuites, $imagens;

    public function __construct()
    {
        $this->imagens = array();
    }

    public function getImagens()
    {
        return $this->imagens;
    }

    public function addImagem($imagem)
    {
        array_push($this->imagens, $imagem);
        return $this;
    }

    public function getCorretorId()
    {
        return $this->corretorId;
    }

    public function setCorretorId($corretorId)
    {
        $this->corretorId = $corretorId;
        return $this;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
        $this->setTituloSlug();
        return $this;
    }

    public function getTamanho()
    {
        return $this->tamanho;
    }

    public function setTamanho($tamanho)
    {
        $this->tamanho = $tamanho;
        return $this;
    }

    public function getTituloSlug()
    {
        return $this->tituloSlug;
    }

    private function setTituloSlug()
    {
        $this->tituloSlug = $this->gerarSlug($this->getTitulo());
        return $this;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
        return $this;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getNumeroQuartos()
    {
        return $this->numeroQuartos;
    }

    public function setNumeroQuartos($numeroQuartos)
    {
        $this->numeroQuartos = $numeroQuartos;
        return $this;
    }

    public function getSituacao()
    {
        return $this->situacao;
    }

    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }

    public function getNumeroBanheiros()
    {
        return $this->numeroBanheiros;
    }

    public function setNumeroBanheiros($numeroBanheiros)
    {
        $this->numeroBanheiros = $numeroBanheiros;
        return $this;
    }

    public function getNumeroVagas()
    {
        return $this->numeroVagas;
    }

    public function setNumeroVagas($numeroVagas)
    {
        $this->numeroVagas = $numeroVagas;
        return $this;
    }

    public function getNumerosSuites()
    {
        return $this->numerosSuites;
    }

    public function setNumerosSuites($numerosSuites)
    {
        $this->numerosSuites = $numerosSuites;
        return $this;
    }

    private function gerarSlug($string)
    {
        $table = array(
            '??' => 'S', '??' => 's', '??' => 'Dj', '??' => 'dj', '??' => 'Z', '??' => 'z', '??' => 'C', '??' => 'c', '??' => 'C', '??' => 'c',
            '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'C', '??' => 'E', '??' => 'E',
            '??' => 'E', '??' => 'E', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'N', '??' => 'O', '??' => 'O', '??' => 'O',
            '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'Y', '??' => 'B', '??' => 'Ss',
            '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'c', '??' => 'e', '??' => 'e',
            '??' => 'e', '??' => 'e', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'o', '??' => 'n', '??' => 'o', '??' => 'o',
            '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'y', '??' => 'y', '??' => 'b',
            '??' => 'y', '??' => 'R', '??' => 'r', '/' => '-', ' ' => '-', "'" => "", ',' => '', '??' => '2',
        );

        // -- Remove duplicated spaces
        $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);

        // -- Returns the slug
        return strtolower(strtr($string, $table));
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


    public function getUsuarioId()
    {
        return $this->usuarioId;
    }

    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;

        return $this;
    }


    public function getImagemCapa()
    {
        return $this->imagemCapa;
    }

    public function setImagemCapa($imagemCapa)
    {
        $this->imagemCapa = $imagemCapa;
        return $this;
    }
}
