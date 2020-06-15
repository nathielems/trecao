<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RotasController extends Controller
{
    public function calcular($start){
        return view('trajeto-roteiro');
    }

    public function selecionarRota(){
        return view('selecao-roteiro');
    }
}
