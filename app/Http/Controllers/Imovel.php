<?php

namespace App\Http\Controllers;

use App\Models\ImovelModel;
use Illuminate\Http\Request;

class Imovel extends Controller
{
    public function criarImovel(Request $request)
    {
        $imoveisData = $request->input('imoveis');

        if (is_null($imoveisData)) {
            return response()->json("Favor, informar valores válidos", 403);
        }

        $imoveis = array();

        foreach ($imoveisData as $imovelData) {

            $imovel = new ImovelModel();
            $imovel->setCorretorId($imovelData['corretorId'])
                ->setTitulo($imovelData['titulo'])
                ->setDescricao($imovelData['descricao'])
                ->setSituacao($imovelData['situacao'])
                ->setTamanho($imovelData['tamanho'])
                ->setPreco($imovelData['preco'])
                ->setNumeroBanheiros($imovelData['numeroBanheiros'])
                ->setNumeroVagas($imovelData['numeroVagas'])
                ->setNumerosSuites($imovelData['numeroSuites'])
                ->setNumeroQuartos($imovelData['numeroQuartos']);

            foreach ($imovelData['imagens'] as $imagem) {
                $imovel->addImagem($imagem);
            }

            array_push($imoveis, $imovel);
        }
    }
}
