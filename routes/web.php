<?php

use Illuminate\Support\Facades\Route;

Route::get('/', ['uses' => 'EditalsController@index', 'as'    => 'edital.index']);

Route::resource('edital', 'EditalsController');

Route::get('/cadastrar',            ['uses' => 'EditalsController@cadastrar',           'as' => 'edital.cadastrar']);
Route::post('/salva_edital',        ['uses' => 'EditalsController@salvar',              'as' => 'edital.salvar']);
Route::post('/salva_edital_anexo',  ['uses' => 'EditalFilhosController@salvar',         'as' => 'editalAnexo.salvar']);
Route::post('/filtrarPorTipoAnexo', ['uses' => 'EditalsController@filtrarPorTipoAnexo', 'as' => 'edital.filtrarPorTipoAnexo']);

Route::get('/filtro/{ano}/1', ['uses' => 'EditalsController@filtrarPorAno', 'as' =>'editais.filtrarPorAno']);
Route::get('/filtro/{ano_selecionado}/{tipo}', ['uses' => 'EditalsController@filtrarPorTipo', 'as' =>'editais.filtrarPorTipo']);

