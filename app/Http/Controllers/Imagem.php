<?php

namespace App\Http\Controllers;

use App\Factory\ImagemFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Imagem extends Controller
{
    private $imagemFactory;

    public function __construct()
    {
        $this->imagemFactory = new ImagemFactory();
    }

    public function atualizarImagemCapaImovel(Request $request)
    {
        if (is_null($request->input('idNovaImagemCapa')) || is_null($request->input('imovelId'))) {
            return response()->json("É preciso informar a idNovaImagemCapa e o imovelId", 404);
        }

        $cabecalho              = $request->header("Authorization");
        $token                  = $this->buscarToken($cabecalho);
        $idNovaImagemCapa       = $request->input("idNovaImagemCapa");
        $imovelId               = $request->input("imovelId");
        $imagemExisteNoBanco    = $this->imagemFactory->getImovelImagem($idNovaImagemCapa, $token->usuario_id, $imovelId);


        if (!$imagemExisteNoBanco) {
            return response()->json("Só é possível atualizar com imagens já existentes no banco.", 404);
        }

        if ($imagemExisteNoBanco[0]->Usuario_Id !== $token->usuario_id) {
            return response()->json("Essa imagem não pertence a esse imóvel/usuário", 404);
        }

        $resultadoUpdate = $this->imagemFactory->updateImagemCapa($imovelId, $imagemExisteNoBanco[0]->Caminho_Imagem);

        if (!$resultadoUpdate) {
            return response()->json("Erro ao atualizar imagem capa, verifique os campos e tente novamente mais tarde.", 404);
        }

        return response()->json("Imagem Capa atualizada com sucesso");
    }

    public function adicionarImagem(Request $request)
    {
        if (is_null($request->input("novasImagens")) || is_null($request->input("idImovel"))) {
            return response()->json("Favor informar as novasImagens[] e o idImovel.", 404);
        }

        $novasImagens   = $request->input("novasImagens");
        $idImovel       = $request->input("idImovel");

        $imagensParaInserir = [];
        $cabecalho = $request->header("Authorization");
        $token = $this->buscarToken($cabecalho);

        foreach ($novasImagens as $caminhoNovaImagem) {
            $imagem = [
                "Id_Imovel"         => $idImovel,
                "Usuario_Id"        => $token->usuario_id,
                "Caminho_Imagem"    => $caminhoNovaImagem
            ];

            array_push($imagensParaInserir, $imagem);
        }
        $resultado = $this->imagemFactory->addImagens($imagensParaInserir);

        if (!$resultado) {
            return response()->json("Erro ao adicionar imagens, verifique as credenciais e tente novamente mais tarde.", 500);
        }

        return response()->json("Imagens adicionadas com sucesso!");
    }

    public function excluirImagem(Request $request)
    {
        if (is_null($request->input("idImagem")) || is_null($request->input("idImovel"))) {
            return response()->json("Favor informar as idImagem e idImovel.", 404);
        }
        $imagemParaExcluir  = $request->input("idImagem");
        $idImovel           = $request->input("idImovel");
        $cabecalho          = $request->header("Authorization");
        $token              = $this->buscarToken($cabecalho);

        $resultado = $this->imagemFactory->removeImagens($idImovel, $imagemParaExcluir, $token->usuario_id);
        if (!$resultado) {
            return response()->json("Erro ao excluir imagem, verifique as credenciais e tente novamente mais tarde.", 500);
        }

        return response()->json("Imagem removida com sucesso");
    }

    public function buscarImagensImovel(Request $request, int $imovelId)
    {
        $imovelId   = filter_var($imovelId, FILTER_VALIDATE_INT);
        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);
        $imagens    = $this->imagemFactory->getImovelImagens($token->usuario_id, $imovelId);

        if (!$imagens) {
            return response()->json("Erro ao buscar imagens, verifique as credenciais e tente novamente mais tarde.", 500);
        }

        return response()->json($imagens, 200);
    }

    private function buscarToken($cabecalho)
    {
        $token = str_replace("Bearer ", "", $cabecalho);
        $tokenDecodificado = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALG')));
        return $tokenDecodificado;
    }
}
