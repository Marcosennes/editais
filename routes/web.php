<?php

use Illuminate\Support\Facades\Route;

Route::get('/', ['uses' => 'EditalsController@index', 'as'    => 'edital.index']);

Route::post('/salva_edital', ['uses' => 'EditalController@salvar', 'as' =>'edital.salvar']);
