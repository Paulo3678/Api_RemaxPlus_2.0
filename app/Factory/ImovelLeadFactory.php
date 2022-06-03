<?php

namespace App\Factory;

use App\Models\ImovelLeadModel;
use Illuminate\Support\Facades\DB;

class ImovelLeadFactory
{
    public function createImovelLead(ImovelLeadModel $imovelLead)
    {
        try {
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
                '{$imovelLead->getDataLead()->format('d/m/Y')}',
                '{$imovelLead->getHorarioLead()->format('H:i')}',
                '{$imovelLead->getEmailCliente()}',
                '{$imovelLead->getTelefoneCliente()}',
                '{$imovelLead->getCidadeCliente()}');
            ");
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getImovelLeads(int $usuarioId, int $imovelId)
    {
        try {
            $busca = DB::select("SELECT * FROM Imovel_Lead WHERE Usuario_Id={$usuarioId} AND Id_Imovel={$imovelId}");
            return $busca;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getImoveisLeads(int $usuarioId)
    {
        try {
            $busca = DB::select("SELECT * FROM Imovel_Lead WHERE Usuario_Id={$usuarioId}");
            return $busca;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
