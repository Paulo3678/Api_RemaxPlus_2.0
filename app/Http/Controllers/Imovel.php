<?php

namespace App\Http\Controllers;

use App\Factory\CorretorFactory;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\ImovelModel;
use Illuminate\Http\Request;
use App\Factory\ImovelFactory;
use App\Http\Controllers\Controller;

use function Ramsey\Uuid\v1;

class Imovel extends Controller
{

    private $imovelFactory;
    private $corretorFactory;

    public function __construct()
    {
        $this->imovelFactory = new ImovelFactory();
        $this->corretorFactory = new CorretorFactory();
    }

    public function criarImovel(Request $request)
    {
        $imoveisData = $request->input('imoveis');

        if (is_null($imoveisData)) {
            return response()->json("Favor, informar valores válidos", 403);
        }

        $usuarioId = $this->buscarToken($request->header("Authorization"))->usuario_id;

        $imoveis = array();

        foreach ($imoveisData as $imovelData) {

            if (
                !isset($imovelData['corretorId']) || !isset($imovelData['titulo']) || !isset($imovelData['descricao']) || !isset($imovelData['situacao']) ||
                !isset($imovelData['tamanho']) || !isset($imovelData['preco']) || !isset($imovelData['numeroBanheiros']) || !isset($imovelData['numeroVagas']) ||
                !isset($imovelData['numeroSuites']) || !isset($imovelData['numeroQuartos']) || !isset($imovelData['imagens']) || !isset($imovelData['tituloSlug'])
            ) {
                return response()->json("Nem todos os dados foram encontrados, favor informar corretorId, titulo, descricao, situacao, tamanho, preco, numeroBanheiros, numeroVagas, numeroSuites, numeroQuartos, imagens, tituloSlug", 404);
            }
            $slug = $imovelData['tituloSlug'];


            $validaSlug = $this->validarSlug($slug);

            if (!$validaSlug) {
                return response()->json('Slug inválido! Não sãoé permitidos caracteres especiais, acentuações ou -- apenas - !', 404);
            }
            $corretores = $this->corretorFactory->getCorretores($usuarioId);

            $corretorExiste = false;

            foreach ($corretores as $corretor) {
                if ($corretor->Id_Corretor == $imovelData['corretorId']) {
                    $corretorExiste = true;
                }
            }
            if ($corretorExiste === false) {
                return response()->json("Alguns dos corretores não pertencem a esse usuário. Favor informe apenas corretores válidos!", 404);
            }
            $imovel = new ImovelModel();
            $imovel->setCorretorId($imovelData['corretorId'])
                ->setTitulo($imovelData['titulo'])
                ->setDescricao($imovelData['descricao'])
                ->setImagemCapa($imovelData['imagens'][0])
                ->setSituacao($imovelData['situacao'])
                ->setTamanho($imovelData['tamanho'])
                ->setPreco($imovelData['preco'])
                ->setNumeroBanheiros($imovelData['numeroBanheiros'])
                ->setNumeroVagas($imovelData['numeroVagas'])
                ->setNumerosSuites($imovelData['numeroSuites'])
                ->setNumeroQuartos($imovelData['numeroQuartos'])
                ->setTituloSlug($slug);

            foreach ($imovelData['imagens'] as $imagem) {
                $imovel->addImagem($imagem);
            }

            array_push($imoveis, $imovel);
        }

        $resultadoSalvamento = $this->imovelFactory->createImovel($imoveis, $usuarioId);

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

        if (is_null($imovelParaExcluir)) {
            return response()->json("Favor informar idImovel que será excluido.", 404);
        }
        $imovel     = $this->imovelFactory->getImovel($imovelParaExcluir);
        $usuarioId  = $imovel['Usuario_ID'];

        if (!$imovel) {
            return response()->json("Imóvel não encontrado, verifique as credencias e tente novamente mais tarde.", 500);
        }

        if ($usuarioId !== $usuarioIdToken) {
            return response()->json("Opss.. esse imóvel não pertence a esse usuario.", 404);
        }

        $resultadoExclusao = $this->imovelFactory->removeImovel($imovelParaExcluir);

        if (!$resultadoExclusao) {
            return response()->json("Erro ao remover imóvel, verifique os campos e tente novamente mais tarde", 500);
        }

        return response()->json("Imóvel removido com sucesso", 200);
    }

    public function buscarImoveis(Request $request)
    {
        $cabecalho = $request->header("Authorization");
        $token = $this->buscarToken($cabecalho);

        $imoveis = $this->imovelFactory->getImoveis($token->usuario_id);
        if (!$imoveis) {
            return response()->json("Erro ao buscar imóveis, tente novamente mais tarde.", 404);
        }

        return response()->json($imoveis, 200);
    }

    public function buscarImovel(Request $request, int $idImovel)
    {
        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);

        $imovel = $this->imovelFactory->getImovel($idImovel);

        if ($imovel['Usuario_ID'] !== $token->usuario_id) {
            return response()->json("Opss... Esse imóvel não pertence a esse usuário.", 404);
        }

        return $imovel;
    }

    public function buscarImovelCorretor(Request $request)
    {
        if (is_null($request->input('corretorId'))) {
            return response()->json("Favor informar o corretorId", 404);
        }

        $cabecalho      = $request->header("Authorization");
        $token          = $this->buscarToken($cabecalho);
        $corretorId     = $request->input('corretorId');
        $resultadoBusca = $this->imovelFactory->getCorretorImoveis($corretorId, $token->usuario_id);

        if (!$resultadoBusca) {
            return response()->json("Erro ao buscar imóveis, verifique as credenciais e tente novamente mais tarde", 404);
        }

        return response()->json($resultadoBusca, 200);
    }

    public function buscarImoveisPorPagina(Request $request, int $pagina)
    {
        $cabecalho = $request->header("Authorization");
        $token = $this->buscarToken($cabecalho);

        return response()->json($this->imovelFactory->getPaginatedImoveis($token->usuario_id, $pagina), 200);
    }

    public function atualizarDadosImovel(Request $request)
    {

        if (
            is_null($request->input('corretorId')) || is_null($request->input('titulo')) || is_null($request->input('descricao')) || is_null($request->input('situacao')) ||
            is_null($request->input('tamanho')) || is_null($request->input('preco')) || is_null($request->input('numeroBanheiros')) || is_null($request->input('numeroVagas')) ||
            is_null($request->input('numeroSuites')) || is_null($request->input('numeroQuartos')) || is_null($request->input('imovelId'))
        ) {
            return response()->json("Nem todos os dados foram encontrados, favor informar corretorId, titulo, descricao, situacao, tamanho, preco, numeroBanheiros, numeroVagas, numeroSuites, numeroQuartos e imovelId", 406);
        }

        $cabecalho = $request->header("Authorization");
        $token = $this->buscarToken($cabecalho);

        $novoImovel = new ImovelModel();

        $imovelId = $request->input("imovelId");
        $imovelAntigo = $this->imovelFactory->getImovel($imovelId);

        if (!$imovelAntigo) {
            return response()->json("Erro ao encontrar imovel, verifique as credenciais e tente novamente mais tarde.", 500);
        }


        $novoImovel->setCorretorId($request->input('corretorId'))
            ->setTitulo($request->input('titulo'))
            ->setUsuarioId($token->usuario_id)
            ->setDescricao($request->input('descricao'))
            ->setSituacao($request->input('situacao'))
            ->setTamanho($request->input('tamanho'))
            ->setPreco($request->input('preco'))
            ->setNumeroBanheiros($request->input('numeroBanheiros'))
            ->setNumeroVagas($request->input('numeroVagas'))
            ->setNumerosSuites($request->input('numeroSuites'))
            ->setNumeroQuartos($request->input('numeroQuartos'));

        if ($token->usuario_id !== $imovelAntigo['Usuario_ID']) {
            return response()->json("Opss... Esso imóvel não pertence a esse usuario");
        }

        $resultadoAtualizacao = $this->imovelFactory->updateImovel($novoImovel, $imovelId);

        if (!$resultadoAtualizacao) {
            return response()->json("Erro ao atualizar imóvel, tente novamente mais tarde", 500);
        }

        return response()->json("Imóvel atualizado com sucesso", 200);
    }

    private function buscarToken($cabecalho)
    {
        $token = str_replace("Bearer ", "", $cabecalho);
        $tokenDecodificado = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALG')));
        return $tokenDecodificado;
    }

    private function validarSlug(string $slug)
    {
        $validacoes = [
            preg_match("/--/", $slug),
            preg_match("/ /", $slug),
            preg_match("/#/", $slug),
            preg_match("/[*]/", $slug),
            preg_match("/,/", $slug),
            preg_match("/[+]/", $slug),
            preg_match("/,/", $slug),
            preg_match("/[.]/", $slug),
            preg_match("/;/", $slug),
            preg_match("/[?]/", $slug),
            preg_match("/'/", $slug),
            preg_match('/"/', $slug),
            preg_match("/}/", $slug),
            preg_match("/{/", $slug),
            preg_match("/]/", $slug),
            preg_match('/~/', $slug),
            preg_match("/`/", $slug),
            preg_match("/´/", $slug),
            preg_match("/á/", $slug),
            preg_match("/à/", $slug),
            preg_match("/Á/", $slug),
            preg_match("/À/", $slug),
            preg_match("/é/", $slug),
            preg_match("/è/", $slug),
            preg_match("/É/", $slug),
            preg_match("/È/", $slug),
            preg_match("/í/", $slug),
            preg_match("/ì/", $slug),
            preg_match("/ì/", $slug),
            preg_match("/Í/", $slug),
            preg_match("/Ì/", $slug),
            preg_match("/ó/", $slug),
            preg_match("/ò/", $slug),
            preg_match("/Ó/", $slug),
            preg_match("/Ò/", $slug),
            preg_match("/ú/", $slug),
            preg_match("/ù/", $slug),
            preg_match("/Ú/", $slug),
            preg_match("/Ù/", $slug),
            preg_match("/[\/]/", $slug),
            preg_match("/\\\\/", $slug),
            preg_match("/[[]/", $slug),
            preg_match("/=/", $slug)
        ];

        foreach ($validacoes as $key => $validacao) {

            if ($validacao) {
                return false;
            }
        }

        return true;
    }
}
