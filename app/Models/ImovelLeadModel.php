<?php

namespace App\Models;

class ImovelLeadModel
{
    private $idImovel, $usuarioId, $dataLead, $emailCliente, $telefoneCliente, $cidadeCliente, $urlLead, $corretorId, $mensagem;

    public function getIdImovel()
    {
        return $this->idImovel;
    }

    public function setIdImovel($idImovel)
    {
        $this->idImovel = $idImovel;
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

    public function getDataLead()
    {
        return $this->dataLead;
    }

    public function setDataLead($dataLead)
    {
        $this->dataLead = $dataLead;
        return $this;
    }


    public function getEmailCliente()
    {
        return $this->emailCliente;
    }

    public function setEmailCliente($emailCliente)
    {
        $this->emailCliente = $emailCliente;
        return $this;
    }

    public function getTelefoneCliente()
    {
        return $this->telefoneCliente;
    }

    public function setTelefoneCliente($telefoneCliente)
    {
        $this->telefoneCliente = $telefoneCliente;
        return $this;
    }

    public function getCidadeCliente()
    {
        return $this->cidadeCliente;
    }

    public function setCidadeCliente($cidadeCliente)
    {
        $this->cidadeCliente = $cidadeCliente;
        return $this;
    }

    public function getUrlLead()
    {
        return $this->urlLead;
    }

    public function setUrlLead($urlLead)
    {
        $this->urlLead = $urlLead;

        return $this;
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;

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
}
