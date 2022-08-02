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
    $router->get('/usuario/usuario', 'Usuario@buscarDadosUsuario');
    $router->get('/usuario/usuario/hierarquia', 'Usuario@verificarHierarquia');
    
    $router->get('/corretor/corretores', 'Corretor@buscarCorretores');
    $router->get('/corretor/corretor/{idCorretorParaBuscar}', 'Corretor@buscarCorretor');

    $router->get('/imovel/corretor/buscar', 'Imovel@buscarImovelCorretor');
    $router->get('/imovel/imoveis', 'Imovel@buscarImoveis');
    $router->get('/imovel/imovel/{idImovel}', 'Imovel@buscarImovel');
    $router->get('/imovel/imovel/pagina/{pagina}', 'Imovel@buscarImoveisPorPagina');

    $router->get('/imovel/imagens/buscar/{imovelId}', 'Imagem@buscarImagensImovel');

    $router->get('/imovel/imoveis/leads', 'ImovelLead@buscarImoveisLeads');
    $router->get('/imovel/imovel/leads/{imovelId}', 'ImovelLead@buscarImovelLeads');
    $router->get('/imovel/imovel/leads/pagina/{paginaLead}', 'ImovelLead@buscarImoveisLeadsPaginado');
    $router->get('/imovel/imovel/leads/corretor/{paginaLead}/{corretorId}', 'ImovelLead@buscarCorretorImoveisLeadsPaginado');

    $router->post('/imovel/admin/leads/{paginaLead}', 'ImovelLead@buscarBuscarLeadsClientes');
    

    // POST
    $router->post('/usuario/cadastrar', "Usuario@cadastrarUsuario");
   
    $router->post('/corretor/criar', 'Corretor@criarCorretor');
   
    $router->post('/imovel/adicionar', 'Imovel@criarImovel');
    $router->post('/imovel/imagem/adicionar', 'Imagem@adicionarImagem');

    $router->post('/imovel/leads/adicionar', 'ImovelLead@criarImovelLead');


    // DELETE
    $router->delete("/usuario/delete", "Usuario@excluirUsuario");
   
    $router->delete("/corretor/delete", "Corretor@excluirCorretor");
   
    $router->delete("/imovel/delete", "Imovel@excluirImovel");
    $router->delete("/imovel/imagem/delete", "Imagem@excluirImagem");

    // PUT
    $router->put("/usuario/atualizar", "Usuario@atualizarUsuario");
    $router->put("/usuario/atualizar/senha", "Usuario@atualizarSenha");
   
    $router->put("/corretor/atualizar", "Corretor@atualizarCorretor");
   
    $router->put("/imovel/atualizar", 'Imovel@atualizarDadosImovel');
    $router->put("/imovel/imagem/atualizar/capa", 'Imagem@atualizarImagemCapaImovel');
});
