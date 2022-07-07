<?php

namespace App\Factory;

use App\Models\ImovelModel;
use Illuminate\Support\Facades\DB;

class ImovelFactory
{
    public function createImovel(array $imoveis, int $usuarioId)
    {
        try {
            DB::beginTransaction();

            /** @var ImovelModel $imovel */
            foreach ($imoveis as $imovel) {
                $idImovelInserido = DB::table("Imovel")->insertGetId([
                    "Corretor_Id" => $imovel->getCorretorId(),
                    "Usuario_ID" => $usuarioId,
                    "Titulo" => $imovel->getTitulo(),
                    "Titulo_Slug" => $imovel->getTituloSlug(),
                    "Imagem_Capa" => $imovel->getImagemCapa(),
                    "Descricao" => $imovel->getDescricao(),
                    "Situacao" => $imovel->getSituacao(),
                    "Tamanho" => $imovel->getTamanho(),
                    "Preco" => $imovel->getPreco(),
                    "Numero_Quartos" => $imovel->getNumeroQuartos(),
                    "Numero_Banheiros" => $imovel->getNumeroBanheiros(),
                    "Numero_Vagas" => $imovel->getNumeroVagas(),
                    "Numero_Suites" => $imovel->getNumerosSuites()
                ]);

                foreach ($imovel->getImagens() as $imagem) {
                    DB::table("Imagem")->insert([
                        "Id_Imovel" => $idImovelInserido,
                        "Usuario_Id" => $usuarioId,
                        "Caminho_Imagem" => $imagem
                    ]);
                }
            }
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
            // return false;
        }
        
    }

    public function removeImovel(int $imovelId)
    {
        try {
            DB::delete("DELETE FROM `remaxplus`.`Imovel` WHERE (`Id` = {$imovelId});");
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getImovel(int $imovelId)
    {
        try {
            $imovelData = DB::select("SELECT * FROM Imovel INNER JOIN Imagem ON Id_Imovel=Imovel.Id WHERE Imovel.Id={$imovelId};");

            $imovel = [
                "Id" => $imovelData[0]->Id,
                "CorretorId" => $imovelData[0]->Corretor_Id,
                "Usuario_ID" => $imovelData[0]->Usuario_ID,
                "Imagem_Capa" => $imovelData[0]->Imagem_Capa,
                "Titulo" => $imovelData[0]->Titulo,
                "Titulo_Slug" => $imovelData[0]->Titulo_Slug,
                "Descricao" => $imovelData[0]->Descricao,
                "Situacao" => $imovelData[0]->Situacao,
                "Tamanho" => $imovelData[0]->Tamanho,
                "Preco" => $imovelData[0]->Preco,
                "Numero_Quartos" => $imovelData[0]->Numero_Quartos,
                "Numero_Banheiros" => $imovelData[0]->Numero_Banheiros,
                "Numero_Vagas" => $imovelData[0]->Numero_Vagas,
                "Numero_Suites" => $imovelData[0]->Numero_Suites,
                "Imagens" => []
            ];


            foreach ($imovelData as $dados) {

                array_push($imovel["Imagens"], [
                    "Id" => $dados->Id_Imagem,
                    "Caminho_Imagem" => $dados->Caminho_Imagem
                ]);
            }

            return $imovel;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getImoveis(int $usuarioId)
    {
        try {
            $imoveis = DB::select("SELECT * FROM Imovel WHERE Usuario_ID={$usuarioId}");
            return $imoveis;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getCorretorImoveis(int $corretorId, $usuarioId)
    {
        try {
            $imoveis = DB::select("SELECT * FROM Imovel WHERE Usuario_ID={$usuarioId} AND Corretor_Id={$corretorId}");

            return $imoveis;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getPaginatedImoveis(int $usuarioId, int $pagina)
    {
        try {
            $pagina = $pagina === 0 ? 1 : $pagina;
            $todos_imoveis = DB::select("SELECT * FROM Imovel");
            // Contar o total de imoveis
            $total_imoveis = count($todos_imoveis);

            // Total de imoveis por pagina
            $imoveis_por_pagina = 6;

            // Calcular o numero de páginas necessárias para apresentar os cursos
            $numero_paginas = ceil($total_imoveis / $imoveis_por_pagina);

            // Calcular o inicio da visualização
            $inicio = ($imoveis_por_pagina * $pagina) - $imoveis_por_pagina;

            // Buscando os imoveis
            $result = DB::select("SELECT * FROM Imovel INNER JOIN Corretor ON Corretor.Id_Corretor=Imovel.Corretor_Id WHERE Usuario_ID={$usuarioId} LIMIT {$inicio}, {$imoveis_por_pagina}");
  
            return [
                "total_paginas" => $numero_paginas,
                "imoveis"       => $result
            ];
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
            // return false;
        }
    }

    public function updateImovel(ImovelModel $imovel, int $imovelId)
    {
        try {
            DB::update("UPDATE `remaxplus`.`Imovel`
            SET
                `Corretor_Id` = {$imovel->getCorretorId()},
                `Usuario_ID` = {$imovel->getUsuarioId()},
                `Titulo` = '{$imovel->getTitulo()}',
                `Titulo_Slug` = '{$imovel->getTituloSlug()}',
                `Descricao` = '{$imovel->getDescricao()}',
                `Situacao` = '{$imovel->getSituacao()}',
                `Tamanho` = {$imovel->getTamanho()},
                `Preco` = '{$imovel->getPreco()}',
                `Numero_Quartos` = {$imovel->getNumeroQuartos()},
                `Numero_Banheiros` = {$imovel->getNumeroBanheiros()},
                `Numero_Vagas` = {$imovel->getNumeroVagas()},
                `Numero_Suites` = {$imovel->getNumerosSuites()}
            WHERE `Id` = {$imovelId};
            ");
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
