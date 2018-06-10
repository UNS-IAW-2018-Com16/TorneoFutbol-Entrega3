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

    $IDFecha = request()->IDFecha;
    $fecha = Fechas::find($IDFecha);
    $arregloPartidos = $fecha->partidos;
    $IDPartido = new \MongoDB\BSON\ObjectId($partido->_id);
    array_push($arregloPartidos, $IDPartido);
    $fecha->partidos = $arregloPartidos;
    $fecha->save();

    return redirect('fixture');
  }

  public function eliminarPartido($id){
    $objectID = new \MongoDB\BSON\ObjectId($id);
    
    $fecha = Fechas::find(request()->IDFecha);
    $arregloPartidos = $fecha->partidos;

    if (($key = array_search($objectID, $arregloPartidos)) !== null) {
      unset($arregloPartidos[$key]);
    }

    $fecha->partidos = $arregloPartidos;
    $fecha->save();

    Partidos::destroy($id);

    return redirect('fixture');
  }

  public function modificarPartido(){
    $IDPartido = request()->IDPartido;
    $partido = Partidos::find($IDPartido);
    $partido->cancha = request()->cancha;
    $partido->fecha = request()->fecha;
    $partido->hora = request()->hora;
    $partido->arbitro = request()->arbitro;
    $equipoLocal = Equipos::where('nombre', request()->equipoLocal)->get()->first()->_id;
    $IDLocal = new \MongoDB\BSON\ObjectId($equipoLocal);
    $equipoVisitante = Equipos::where('nombre', request()->equipoVisita)->get()->first()->_id;
    $IDVisita = new \MongoDB\BSON\ObjectId($equipoVisitante);
    $partido->equipoLocal = $IDLocal;
    $partido->equipoVisitante = $IDVisita;
    $partido->golesLocal = request()->golesLocal;
    $partido->golesVisita = request()->golesVisita;

    $partido->save();

    return redirect('fixture');
  }

}