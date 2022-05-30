<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->post('/usuario/login', "Login@login");

// Rotas que vão requisitar o login
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    // GET
    $router->get('/usuario/usuarios', 'Usuario@buscarTodosUsuarios');
    $router->get('/usuario/usuario/{usuarioId}', 'Usuario@buscarDadosUsuario');
    $router->get('/corretor/corretores', 'Corretor@buscarCorretores');
    $router->get('/corretor/corretor/{idCorretorParaBuscar}', 'Corretor@buscarCorretor');

    // POST
    $router->post('/usuario/cadastrar', "Usuario@cadastrarUsuario");
    $router->post('/corretor/criar', 'Corretor@criarCorretor');
    $router->post('/imovel/adicionar', 'Imovel@criarImovel');

    // DELETE
    $router->delete("/usuario/delete", "Usuario@excluirUsuario");
    $router->delete("/corretor/delete", "Corretor@excluirCorretor");

    // PUT
    $router->put("/usuario/atualizar", "Usuario@atualizarUsuario");
    $router->put("/usuario/atualizar/senha", "Usuario@atualizarSenha");
    $router->put("/corretor/atualizar", "Corretor@atualizarCorretor");
});
