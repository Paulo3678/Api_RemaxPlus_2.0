<?php

namespace App\Factory;

use App\Models\ImovelLeadModel;
use Illuminate\Support\Facades\DB;

class ImovelLeadFactory
{
    public function createImovelLead(ImovelLeadModel $imovelLead)
    {
        DB::insert("INSERT INTO `remaxplus`.`Imovel_Lead`
        (
        `Id_Imovel`,
        `Usuario_Id`,
        `Data_Lead`,
        `Horario_Lead`,
        `Email_Cliente`,
        `Telefone_Cliente`,
        `Cidade_Cliente`)
        VALUES
        (
        {$imovelLead->getIdImovel()},
        {$imovelLead->getUsuarioId()},
        {$imovelLead->getDataLead()},
        {$imovelLead->getHorarioLead()},
        {$imovelLead->getEmailCliente()},
        {$imovelLead->getTelefoneCliente()},
        {$imovelLead->getCidadeCliente()});
        ");
    }
}
