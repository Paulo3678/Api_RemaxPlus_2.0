<?php

namespace App\Http\Controllers;

use DateTime;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Factory\ImovelFactory;
use App\Models\ImovelLeadModel;
use PhpParser\Node\Expr\FuncCall;
use App\Factory\ImovelLeadFactory;
use App\Http\Controllers\Controller;
use PHPUnit\Runner\AfterLastTestHook;

class ImovelLead extends Controller
{
    private $imovelLeadFactory;
    private $imovelFactory;

    public function __construct()
    {
        $this->imovelLeadFactory = new ImovelLeadFactory();
        $this->imovelFactory = new ImovelFactory();
    }

    public function buscarImovelLeads(Request $request, int $imovelId)
    {
        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);


        // Imovel pertence a esse usuário ?
        $imovel = $this->imovelFactory->getImovel($imovelId);

        if ($imovel['Usuario_ID'] !== $token->usuario_id) {
            return response()->json("Opss... Esse imóvel não pertence a esse usuário.", 404);
        }
        
        $resultadoBusca = $this->imovelLeadFactory->getImovelLeads($token->usuario_id, $imovelId);
        return response()->json($resultadoBusca, 200);
    }

    public function buscarImoveisLeads(Request $request)
    {
        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);

        $busca  = $this->imovelLeadFactory->getImoveisLeads($token->usuario_id, 0);

        if (!$busca) {
            return response()->json("Erro ao buscar ImoveisLeads, verifique as credenciais e tente novamente mais tarde", 500);
        }

        return $busca;
    }

    public function buscarImoveisLeadsPaginado(Request $request, int $paginaLead)
    {
        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);

        if ($paginaLead < 1) {
            $paginaLead = 1;
        }

        $busca  = $this->imovelLeadFactory->getPaginatedImoveisLeads($token->usuario_id, $paginaLead);

        if (!$busca) {
            return response()->json("Erro ao buscar ImoveisLeads, verifique as credenciais e tente novamente mais tarde", 500);
        }

        return $busca;
    }

    // public function buscarCorretorImoveisLeadsPaginado(Request $request, int $paginaLead, int $corretorId)
    // {
    //     $cabecalho  = $request->header("Authorization");
    //     $token      = $this->buscarToken($cabecalho);

    //     if ($paginaLead < 1) {
    //         $paginaLead = 1;
    //     }

    //     $busca  = $this->imovelLeadFactory->getPaginatedCorretorImoveisLeads($token->usuario_id, $paginaLead, $corretorId);

    //     if (!$busca) {
    //         return response()->json("Erro ao buscar ImoveisLeads, verifique as credenciais e tente novamente mais tarde", 500);
    //     }

    //     return $busca;
    // }

    public function criarImovelLead(Request $request)
    {
        if (
            is_null($request->input("idImovel")) || is_null($request->input("dataLead")) ||
            is_null($request->input("urlLead")) || is_null($request->input("emailCliente")) ||
            is_null($request->input("telefoneCliente")) || is_null($request->input("cidadeCliente")) ||
            is_null($request->input("mensagem")) || is_null($request->input("corretorId"))
        ) {
            return response()->json("Favor informar idImovel, dataLead, horarioLead, urlLead, emailCliente, telefoneCliente, cidadeCliente, corretorId, mensagem", 404);
        }

        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);

        $imovelLead = new ImovelLeadModel();
        $imovelLead->setIdImovel($request->input("idImovel"))
            ->setUsuarioId($token->usuario_id)
            ->setDataLead($request->input("dataLead"))
            ->setEmailCliente($request->input("emailCliente"))
            ->setTelefoneCliente($this->validadarTelefone($request->input("telefoneCliente")))
            ->setCidadeCliente($request->input("cidadeCliente"))
            ->setMensagem($request->input("mensagem"))
            ->setCorretorId($request->input("corretorId"))
            ->setUrlLead($request->input("urlLead"));

        $resultadoCreate = $this->imovelLeadFactory->createImovelLead($imovelLead);

        if (!$resultadoCreate) {
            return response()->json("Erro ao criar ImovelLead, verifique as credenciais e tente novamente mais tarde", 500);
        }

        return response()->json("ImovelLead criado com sucesso.", 200);
    }

    private function buscarToken($cabecalho)
    {
        $token = str_replace("Bearer ", "", $cabecalho);
        $tokenDecodificado = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALG')));
        return $tokenDecodificado;
    }

    // BUSCAR LEADS DE TODOS USUARIOS
    public function buscarBuscarLeadsClientes(Request $request, int $paginaLead)
    {
        $cabecalho  = $request->header("Authorization");
        $usuarioId = $request->usuarioId;

        if (is_null($usuarioId)) {
            return response()->json(["Favor informar usuarioId!"], 404);
        }

        $gerarPlanilha = $request->query("gerarPlanilha");

        if ($gerarPlanilha === null) {
            $busca = $this->imovelLeadFactory->getImoveisLeads($usuarioId, $paginaLead);
            return response()->json($busca, 200);
        }

        $busca = $this->imovelLeadFactory->getImoveisLeads($usuarioId, $paginaLead, false);
        return response()->json($busca, 200);
    }

    private function validadarTelefone(string $telefone)
    {
        $regex = "/[+][0-9]{2} [0-9]{2} [9][0-9]{4}[-][0-9]{4}/";
        $validacao = preg_match($regex, $telefone);

        if (!$validacao) {
            return "+35 51 99999-9999";
        }
        return $telefone;
    }
}
