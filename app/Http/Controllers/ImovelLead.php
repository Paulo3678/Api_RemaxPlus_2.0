<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Models\ImovelLeadModel;
use App\Factory\ImovelLeadFactory;
use App\Http\Controllers\Controller;
use PhpParser\Node\Expr\FuncCall;

class ImovelLead extends Controller
{
    private $imovelLeadFactory;

    public function __construct()
    {
        $this->imovelLeadFactory = new ImovelLeadFactory();
    }

    public function buscarImovelLeads(int $imovelId)
    {
    }

    public function buscarImoveisLeads(int $usuarioId)
    {
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

        $idImovel = $request->input("idImovel");
        $dataLead = $request->input("dataLead");
        $HorarioLead = $request->input("horarioLead");
        $EmailCliente = $request->input("emailCliente");
        $TelefoneCliente = $request->input("telefoneCliente");
        $CidadeCliente = $request->input("cidadeCliente");


        $imovelLead = new ImovelLeadModel();
        $imovelLead->setIdImovel($request->input("idImovel"))
            ->setUsuarioId($token->usuario_id)
            ->setDataLead($request->input("dataLead"))
            ->setHorarioLead($request->input("horarioLead"))
            ->setEmailCliente($request->input("emailCliente"))
            ->setTelefoneCliente($request->input("telefoneCliente"))
            ->setCidadeCliente($request->input("cidadeCliente"));
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
    }
}
