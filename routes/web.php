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

    // POST
    $router->post('/usuario/cadastrar', "Usuario@cadastrarUsuario");

    // DELETE
    $router->delete("/usuario/delete", "Usuario@excluirUsuario");

});
