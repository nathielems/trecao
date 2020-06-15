<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/selecao-roteiro', 'RotasController@selecionarRota')->name('selecionar-rota');

Route::get('/trajeto-roteiro/{$start}', 'RotasController@calcular')->name('calcular');