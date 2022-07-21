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

    public function setTituloSlug(string $slug)
    {
        // $this->tituloSlug = $this->gerarSlug($this->getTitulo());
        $this->tituloSlug = $slug;
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

    // private function gerarSlug($string)
    // {
    //     $table = array(
    //         'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
    //         'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
    //         'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
    //         'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
    //         'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
    //         'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
    //         'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
    //         'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '/' => '-', ' ' => '-', "'" => "", ',' => '', '²' => '2',
    //     );

    //     // -- Remove duplicated spaces
    //     $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);

    //     // -- Returns the slug
    //     return strtolower(strtr($string, $table));
    // }

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
