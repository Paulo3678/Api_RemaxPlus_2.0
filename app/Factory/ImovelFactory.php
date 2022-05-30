<?php

namespace App\Factory;

use App\Models\ImovelModel;
use Illuminate\Support\Facades\DB;

class ImovelFactory
{
    public function salvarImovel(array $imoveis)
    {
        DB::beginTransaction();

        /** @var ImovelModel $imovel */
        foreach ($imoveis as $imovel) {
            $idImovelInserido = DB::table("Imovel")->insertGetId([
                "Corretor_Id" => $imovel->getCorretorId(),
                "Titulo" => $imovel->getTitulo(),
                "Titulo_Slug" => $imovel->getTituloSlug(),
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
                    "Caminho_Imagem" => $imagem
                ]);
            }
        }

        DB::commit();
    }
}
