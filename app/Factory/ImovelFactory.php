<?php

namespace App\Factory;

use Illuminate\Support\Facades\DB;

class ImovelFactory
{
    public function salvarImovel(array $imoveis)
    {
        DB::beginTransaction();

        foreach ($imoveis as $imovel) {
            DB::insert("INSERT INTO Imovel (Corretor_Id, Titulo, Titulo_Slug, Descricao, 
                Situacao, Tamanho, Preco, Numero_Quartos, Numero_Banheiros, 
                Numero_Vagas, Numero_Suites) VALUES (

            );");
        }

        DB::commit();
    }
}
