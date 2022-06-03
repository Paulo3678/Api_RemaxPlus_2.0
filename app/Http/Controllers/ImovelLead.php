<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\ImovelLeadModel;
use App\Factory\ImovelLeadFactory;
use App\Http\Controllers\Controller;
use DateTime;
use PhpParser\Node\Expr\FuncCall;

class ImovelLead extends Controller
{
    private $imovelLeadFactory;

    public function __construct()
    {
        $this->imovelLeadFactory = new ImovelLeadFactory();
    }

    public function buscarImovelLeads(Request $request, int $imovelId)
    {

        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);

        $resultadoBusca = $this->imovelLeadFactory->getImovelLeads($token->usuario_id, $imovelId);
        if (!$resultadoBusca) {
            return response()->json("Erro ao buscar ImovelLeads, verifique as credenciais e tente novamente mais tarde", 500);
        }
        return response()->json($resultadoBusca, 200);
    }

    public function buscarImoveisLeads(Request $request)
    {
        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);

        $busca  = $this->imovelLeadFactory->getImoveisLeads($token->usuario_id);
        
        if (!$busca) {
            return response()->json("Erro ao buscar ImoveisLeads, verifique as credenciais e tente novamente mais tarde", 500);
        }

        return $busca;
    }

    public function criarImovelLead(Request $request)
    {
        if (
            is_null($request->input("idImovel")) || is_null($request->input("dataLead")) || is_null($request->input("horarioLead")) ||
            is_null($request->input("emailCliente")) || is_null($request->input("telefoneCliente")) || is_null($request->input("cidadeCliente"))
        ) {
            return response()->json("Favor informar idImovel, dataLead, horarioLead, emailCliente, telefoneCliente, cidadeCliente", 404);
        }

        $cabecalho  = $request->header("Authorization");
        $token      = $this->buscarToken($cabecalho);

        $imovelLead = new ImovelLeadModel();
        $imovelLead->setIdImovel($request->input("idImovel"))
            ->setUsuarioId($token->usuario_id)
            ->setDataLead($this->validarDataLead($request->input("dataLead")))
            ->setHorarioLead($this->validarHorarioLead($request->input("horarioLead")))
            ->setEmailCliente($request->input("emailCliente"))
            ->setTelefoneCliente($this->validadarTelefone($request->input("telefoneCliente")))
            ->setCidadeCliente($request->input("cidadeCliente"));

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

    private function validarAdm(int $userId)
    {
        $usuario = $this->usuarioFactory->getUsuario(['id', $userId]);
        $hierarquiaUsuario = $usuario->getHierarquia();

        if ($hierarquiaUsuario !== "adm") {
            return false;
        }

        return true;
    }

    private function validarDataLead(string $dataLead)
    {
        $data = DateTime::createFromFormat("d/m/Y", $dataLead);

        if (!checkdate($data->format('m'), $data->format('d'), $data->format('Y'))) {
            return DateTime::createFromFormat("d/m/Y", "01/12/2004");
        }
        return $data;
    }


    private function validarHorarioLead(string $horario)
    {
        $horario = DateTime::createFromFormat("!H:i", $horario);

        if (!$horario) {
            return DateTime::createFromFormat('!H:i', "00:00");
        }

        return $horario;
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
