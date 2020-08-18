<?php

use Illuminate\Support\Facades\Route;

Route::get('/', ['uses' => 'EditalsController@index', 'as'    => 'edital.index']);

Route::resource('edital', 'EditalsController');

Route::get('/cadastrar',            ['uses' => 'EditalsController@cadastrar',           'as' => 'edital.cadastrar']);
Route::post('/salva_edital',        ['uses' => 'EditalsController@salvar',              'as' => 'edital.salvar']);
Route::post('/salva_edital_anexo',  ['uses' => 'EditalFilhosController@salvar',         'as' => 'editalAnexo.salvar']);
Route::post('/filtrarAnexo',        ['uses' => 'EditalsController@filtrarAnexo',        'as' => 'edital.filtrarAnexo']);
Route::get('/filtrar/{instituicao_selecionada}/{ano_selecionado}/{tipo_id}', ['uses' => 'EditalsController@filtrar', 'as' =>'editais.filtrar']);
Route::post('/filtrarpost', ['uses' => 'EditalsController@filtrarPost', 'as' =>'editais.filtrarPost']);
