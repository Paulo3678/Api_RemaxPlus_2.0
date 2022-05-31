<?php

namespace App\Models;

class ImovelLeadModel
{
    private $idImovel, $usuarioId, $dataLead, $horarioLead, $emailCliente, $telefoneCliente, $cidadeCliente;

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

    public function getHorarioLead()
    {
        return $this->horarioLead;
    }

    public function setHorarioLead($horarioLead)
    {
        $this->horarioLead = $horarioLead;
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
}
