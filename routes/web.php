<?php

use Illuminate\Support\Facades\Route;
// Rotas sem autenticação
Route::get('/',                 ['uses' => 'EditalsController@index',       'as'        => 'edital.index']);
Route::get('/login',            ['uses' => 'UsersController@login',         'as'        => 'login.loginPage']);
Route::post('/efetua_login',    ['uses' => 'UsersController@autenticar',    'as'        =>'login.autenticar']);
Route::get('/filtrar/{instituicao_selecionada}/{ano_selecionado}/{tipo_id}', ['uses' => 'EditalsController@filtrar', 'as' =>'editais.filtrar']);

Route::group(['middleware' => 'autenticar.login'], function() {
    
    //Rotas somente para usuários logados
    Route::get('/cadastrar',            ['uses' => 'EditalsController@cadastrar',       'as' => 'edital.cadastrar']);
    Route::post('/salva_edital',        ['uses' => 'EditalsController@salvar',          'as' => 'edital.salvar']);
    Route::post('/salva_edital_anexo',  ['uses' => 'EditalFilhosController@salvar',     'as' => 'editalAnexo.salvar']);
    Route::post('/filtrarAnexo',        ['uses' => 'EditalsController@filtrarAnexo',    'as' => 'edital.filtrarAnexo']);

    /*
     * Rota '/filtrarpost' sem funcionalidade por enquanto. A intenção era facilitar a implementação de requisição ajax na index com
     * uma chamada post ao invés de usar uma rota get. Não pode ser utilizada a mesma rota utilizada sem a requisição ajax
     * pois ela retorna uma view. Precisamos de uma função que retorne somente o objeto em json.
     */
    //Route::post('/filtrarpost',         ['uses' => 'EditalsController@filtrarPost',     'as' =>'editais.filtrarPost']); 

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

