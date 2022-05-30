<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\ImovelModel;
use Illuminate\Http\Request;
use App\Factory\ImovelFactory;
use App\Http\Controllers\Controller;

class Imovel extends Controller
{

    private $imovelFactory;

    public function __construct()
    {
        $this->imovelFactory = new ImovelFactory();
    }

    public function criarImovel(Request $request)
    {
        $imoveisData = $request->input('imoveis');

        if (is_null($imoveisData)) {
            return response()->json("Favor, informar valores válidos", 403);
        }

        $imoveis = array();

        foreach ($imoveisData as $imovelData) {

            if (
                !isset($imovelData['corretorId']) || !isset($imovelData['titulo']) || !isset($imovelData['descricao']) || !isset($imovelData['situacao']) ||
                !isset($imovelData['tamanho']) || !isset($imovelData['preco']) || !isset($imovelData['numeroBanheiros']) || !isset($imovelData['numeroVagas']) ||
                !isset($imovelData['numeroSuites']) || !isset($imovelData['numeroQuartos']) || !isset($imovelData['imagens'])
            ) {
                return response()->json("Nem todos os dados foram encontrados, favor informar corretorId, titulo, descricao, situacao, tamanho, preco, numeroBanheiros, numeroVagas, numeroSuites, numeroQuartos, imagens", 406);
            }

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

        $resultadoSalvamento = $this->imovelFactory->createImovel($imoveis);
        if (!$resultadoSalvamento) {
            return response()->json("Erro ao salvar imóvel. Tente novamente mais tarde!", 503);
        }

        return response()->json("Imóvel(is) salvo(s) com sucesso!", 200);
    }

    public function excluirImovel(Request $request)
    {
        $cabecalho = $request->header("Authorization");
        $token = $this->buscarToken($cabecalho);

        $usuarioIdToken = $token->usuario_id; 

        $imovelParaExcluir = $request->input("idImovel");

        if(is_null($imovelParaExcluir)){
            return response()->json("Favor informar idImovel que será excluido.", 404);
        }

        if($this->imovelFactory->getImovel($imovelParaExcluir)->getUsuarioId() !== $usuarioIdToken){
            return response()->json("Opss.. esse imóvel não pertence a esse usuario.", 404);
        }


        $resultadoExclusao = $this->imovelFactory->removeImovel($imovelParaExcluir);

        if(!$resultadoExclusao){
            return response()->json("Erro ao remover imóvel, verifique os campos e tente novamente mais tarde", 500);
        }

        return response()->json("Imóvel removido com sucesso", 200);

    }

    private function buscarToken($cabecalho)
    {
        $token = str_replace("Bearer ", "", $cabecalho);
        $tokenDecodificado = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALG')));
        return $tokenDecodificado;
    }

    private function validarAdm($cabecalho)
    {
        $token = $this->buscarToken($cabecalho);
        if ($token->adm !== "adm") {
            return false;
        }

        return true;
    }
}
