<?php

namespace App\Providers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use App\Factory\UsuarioFactory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // VERIFICADOR DO TOKEN, "null" para erro na busca
        $this->app['auth']->viaRequest('api', function (Request $request) {
            $usuarioFactory = new UsuarioFactory();

            $cabecalho = $request->header("Authorization");
            $token = str_replace("Bearer ", "", $cabecalho);

            $userId = JWT::decode($token, new Key(env('JWT_KEY'), env('JWT_ALG')))->usuario_id;

            $usuario = $usuarioFactory->getUsuario(["id", $userId]);

            if(!$usuario){
                return null;
            }

            return $usuario;
        });
    }
}
