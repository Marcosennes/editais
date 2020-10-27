<?php

use Illuminate\Support\Facades\Route;
// Rotas sem autenticação
Route::get('/',                 ['uses' => 'EditalsController@index',       'as'        => 'edital.index']);
Route::get('/login',            ['uses' => 'UsersController@login',         'as'        => 'login.loginPage']);
Route::post('/efetua_login',    ['uses' => 'UsersController@autenticar',    'as'        =>'login.autenticar']);
Route::get('/filtrar/{instituicao_selecionada}/{ano_selecionado}/{tipo_id}', ['uses' => 'EditalsController@filtrar', 'as' =>'editais.filtrar']);

Route::get('/teste', function(){
    return view('edital.teste');
});

/*
* Rota '/filtrarpost' sem funcionalidade por enquanto. A intenção era facilitar a implementação de requisição ajax na index com
* uma chamada post ao invés de usar uma rota get. Não pode ser utilizada a mesma rota utilizada sem a requisição ajax
* pois ela retorna uma view. Precisamos de uma função que retorne somente o objeto em json.
*/
Route::post('/filtrarpost',         ['uses' => 'EditalsController@filtrarPost',         'as' =>'editais.filtrarPost']); 

Route::post('/filtrar_instituicao',  ['uses' => 'EditalsController@filtrarInstituicao',  'as' =>'editais.filtrar_instituicao']); 

Route::group(['middleware' => 'autenticar.login'], function() {
    
    //Rotas somente para usuários logados
    Route::get('/menu_admin',           ['uses' => 'EditalsController@menuAdmin',           'as' => 'edital.menuAdmin']);
    Route::get('/cadastrar',            ['uses' => 'EditalsController@cadastrar',           'as' => 'edital.cadastrar']);
    Route::get('/cadastrar_anexo',      ['uses' => 'EditalsController@cadastrarAnexo',      'as' => 'edital.cadastrarAnexo']);
    Route::post('/salva_edital',        ['uses' => 'EditalsController@salvar',              'as' => 'edital.salvar']);
    Route::post('/exclui_edital',       ['uses' => 'EditalsController@excluir',             'as' => 'edital.excluir']);
    Route::post('/restaura_edital',     ['uses' => 'EditalsController@restaurar',           'as' => 'edital.restaurar']);
    Route::post('/exclui_anexo',        ['uses' => 'EditalFilhosController@excluir',        'as' => 'editalFilhos.excluir']);
    Route::post('/restaura_anexo',      ['uses' => 'EditalFilhosController@restaurar',      'as' => 'editalFilhos.restaurar']);
    Route::post('/salva_edital_anexo',  ['uses' => 'EditalFilhosController@salvar',         'as' => 'editalAnexo.salvar']);
    Route::post('/filtrarEdital',       ['uses' => 'EditalsController@filtrarEdital',       'as' => 'edital.filtrarEdital']);
    Route::post('/filtrarAnexo',        ['uses' => 'EditalFilhosController@filtrarAnexo',   'as' => 'editalFilhos.filtrarAnexo']);

    /*
    //Rota para registrar novo usuário com privilégios. Se desativada a inserção de novos usuários deve ser realizada direto do banco de dados
    Route::post('/registra',            ['uses' => 'UsersController@registrar',         'as' =>'login.registrar']);
    */

    //Rota para deslogar usuário
    Route::get('/logout', function(){
        Auth::logout();

        return redirect()->route('edital.index');
    })->name('logout');
});

