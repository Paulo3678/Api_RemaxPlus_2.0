<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Factory\UsuarioFactory;

class ValidaAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function __construct()
    {
        $this->usuarioFactory = new UsuarioFactory();
    }
    public function handle(Request $request, Closure $next)
    {
        $validate = $this->validarAdmin($request);

        if (!$validate) {
            return response()->json(["Opss... Essa página só é permitida para usuários administradores"], 403);
        }
        
        // FAZ A VALIDAÇÂO E SE DER TUDO OK ELA DA PROSSEGUIMENTO PARA O CONTROLLER
        return $next($request);
    }

    private function validarAdmin(Request $request)
    {
        $cabecalhoHttp = $request->header("Authorization");
        $token = $this->buscarToken($cabecalhoHttp);
        $usuarioEhAdm = $this->validarAdm($token->usuario_id);

        $usuarioEhAdm = $this->validarAdm($token->usuario_id);

        return $usuarioEhAdm;
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

    private function buscarToken($cabecalho)
    {
        $token = str_replace("Bearer ", "", $cabecalho);
        $tokenDecodificado = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALG')));
        return $tokenDecodificado;
    }
}
