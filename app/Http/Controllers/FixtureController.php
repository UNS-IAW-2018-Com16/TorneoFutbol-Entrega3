<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fechas;
use App\Partidos;
use App\Equipos;
use App\Http\Controllers\JoinTablas;

class FixtureController extends Controller
{
  public function index(){
    $helper = new JoinTablas();
    $fechas = $helper->juntarFechasPartidos(Fechas::all());
    
    return view('fixture', [ 'fechas' => $fechas ]);
  }

  public function agregarFecha(){
    $fecha = new Fechas;
    $fecha->partidos = [];
    $fecha->save();
    return redirect('fixture');
  }

  public function nuevoPartido(){
    $partido = new Partidos;

    $IDLocal = Equipos::where('nombre', request()->equipoLocal)->get()->first()->_id;
    $IDVisita = Equipos::where('nombre', request()->equipoVisita)->get()->first()->_id;
    
    
  	$partido->cancha = request()->cancha;
  	$partido->fecha = request()->fecha;
  	$partido->hora = request()->hora;
  	$partido->arbitro = request()->arbitro;
    $partido->equipoLocal = new \MongoDB\BSON\ObjectId($IDLocal);
    $partido->equipoVisitante = new \MongoDB\BSON\ObjectId($IDVisita);
    $partido->golesLocal = null;
    $partido->golesVisita = null;

    $partido->save();

    dd($partido);
    //guardar en el arreglo de fecha

    return redirect('fixture');

  }

  public function eliminarPartido($id){
    $helper = new JoinTablas();
    $fechas = $helper->juntarFechasPartidos(Fechas::all());

    $encontre = false;

    for ($i=0; $i < count($fechas) && !$encontre ; $i++) { 

      $arregloPartidos = $fechas[$i]->partidos;

      for ($j=0; $j < count($arregloPartidos) && !$encontre ; $j++) { 
          if ($arregloPartidos[$j]->_id == $id){
            $partido = $arregloPartidos[$j];
            $partido->delete();
            $encontre = true;
          } 
      }

      Partidos::destroy($id);
    }
  }

}