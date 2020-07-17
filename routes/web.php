<?php

use Illuminate\Support\Facades\Route;

Route::get('/', ['uses' => 'EditalsController@index', 'as'    => 'edital.index']);
Route::get('/cadastrar', ['uses' => 'EditalsController@cadastrar', 'as'    => 'edital.cadastrar']);

Route::post('/salva_edital', ['uses' => 'EditalsController@salvar', 'as' =>'edital.salvar']);
