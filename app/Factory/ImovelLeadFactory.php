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
            `Corretor_Id`,
            `Data_Lead`,
            `Url_Lead`,
            `Email_Cliente`,
            `Telefone_Cliente`,
            `Cidade_Cliente`,
            `Mensagem`)
            VALUES(
                    {$imovelLead->getIdImovel()},
                    {$imovelLead->getUsuarioId()},
                    {$imovelLead->getCorretorId()},
                    '{$imovelLead->getDataLead()}',
                    '{$imovelLead->getUrlLead()}',
                    '{$imovelLead->getEmailCliente()}',
                    '{$imovelLead->getTelefoneCliente()}',
                   '{$imovelLead->getCidadeCliente()}',
                    '{$imovelLead->getMensagem()}'
                )    
            ");
            return true;
        } catch (\Throwable $e) {
            echo $e->getMessage();
            // return false;
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

    public function getImoveisLeads(int $usuarioId, int $pagina, bool $paginado = true)
    {
        try {
            if ($paginado) {
                $pagina = $pagina === 0 ? 1 : $pagina;
                $todos_imoveis = DB::select("SELECT * FROM Imovel_Lead WHERE Usuario_Id={$usuarioId}");
                // Contar o total de imoveis
                $total_imoveis = count($todos_imoveis);

                // Total de imoveis por pagina
                $leads_por_pagina = 10;

                // Calcular o numero de páginas necessárias para apresentar os cursos
                $numero_paginas = ceil($total_imoveis / $leads_por_pagina);

                // Calcular o inicio da visualização
                $inicio = ($leads_por_pagina * $pagina) - $leads_por_pagina;

                // Buscando os imoveis
                // $result = DB::select("SELECT * FROM ImovelLeads INNER JOIN Corretor ON Corretor.Id_Corretor=Imovel.Corretor_Id WHERE Usuario_ID={$usuarioId} LIMIT {$inicio}, {$imoveis_por_pagina}");
                $result = DB::select("SELECT * FROM Imovel_Lead WHERE Usuario_Id={$usuarioId} LIMIT {$inicio}, {$leads_por_pagina};");

                return [
                    "total_paginas" => $numero_paginas,
                    "leads"       => $result
                ];
            }else{
                $todos_imoveis = DB::select("SELECT * FROM Imovel_Lead WHERE Usuario_Id={$usuarioId}");
                return [
                    "leads" =>  $todos_imoveis
                ];
            }
        } catch (\Throwable $e) {
            return false;
        }

    }

    public function getPaginatedImoveisLeads(int $usuarioId, int $pagina, bool $paginado = true)
    {
        try {
            if ($paginado) {
                $pagina = $pagina === 0 ? 1 : $pagina;
                $todos_imoveis = DB::select("SELECT * FROM Imovel_Lead WHERE Imovel_Lead.Usuario_Id={$usuarioId}");
                // Contar o total de imoveis
                $total_imoveis = count($todos_imoveis);

                // Total de imoveis por pagina
                $leads_por_pagina = 10;

                // Calcular o numero de páginas necessárias para apresentar os cursos
                $numero_paginas = ceil($total_imoveis / $leads_por_pagina);

                // Calcular o inicio da visualização
                $inicio = ($leads_por_pagina * $pagina) - $leads_por_pagina;

                // Buscando os imoveis
                // $result = DB::select("SELECT * FROM ImovelLeads INNER JOIN Corretor ON Corretor.Id_Corretor=Imovel.Corretor_Id WHERE Usuario_ID={$usuarioId} LIMIT {$inicio}, {$imoveis_por_pagina}");
                $result = DB::select("SELECT * FROM Imovel_Lead WHERE Imovel_Lead.Usuario_Id={$usuarioId} LIMIT {$inicio}, {$leads_por_pagina};");

                return [
                    "total_paginas" => $numero_paginas,
                    "leads"       => $result
                ];
            }
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getPaginatedCorretorImoveisLeads(int $usuarioId, int $pagina, int $corretorId)
    {
        try {
            $pagina = $pagina === 0 ? 1 : $pagina;
            $todos_imoveis = DB::select("SELECT * FROM Imovel");
            // Contar o total de imoveis
            $total_imoveis = count($todos_imoveis);

            // Total de imoveis por pagina
            $leads_por_pagina = 10;

            // Calcular o numero de páginas necessárias para apresentar os cursos
            $numero_paginas = ceil($total_imoveis / $leads_por_pagina);

            // Calcular o inicio da visualização
            $inicio = ($leads_por_pagina * $pagina) - $leads_por_pagina;

            // Buscando os imoveis
            // $result = DB::select("SELECT * FROM ImovelLeads INNER JOIN Corretor ON Corretor.Id_Corretor=Imovel.Corretor_Id WHERE Usuario_ID={$usuarioId} LIMIT {$inicio}, {$imoveis_por_pagina}");
            $result = DB::select("SELECT * FROM Imovel_Lead WHERE Corretor_Id={$corretorId} LIMIT {$inicio}, {$leads_por_pagina};");

            return [
                "total_paginas" => $numero_paginas,
                "leads"       => $result
            ];
        } catch (\Throwable $e) {
            return false;
        }
    }
}
