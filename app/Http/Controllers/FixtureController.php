<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fechas;

class FixtureController extends Controller
{
  public function index(){
  	$fechas = Fechas::all();
    return view('fixture', [ 'fechas' => $fechas ]);
  }

  public function nuevoPartido(){
  	$equipoLocal = request()->equipoLocal;
  	$equipoVisita = request()->equipoVisita;
  	$cancha = request()->cancha;
  	$fecha = request()->fecha;
  	$hora = request()->hora;
  	$arbitro = request()->arbitro;

  	

  	dd(request()->equipoLocal);
  }

}