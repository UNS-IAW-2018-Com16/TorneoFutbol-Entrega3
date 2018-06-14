<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fechas;
use App\Partidos;
use App\Equipos;
use App\Usuarios;
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

  public function eliminarFecha($id){
    $fecha = Fechas::find($id);

    $partidos = $fecha->partidos;
    $editores = Usuarios::where('esEditor', true)->get();
    foreach ($partidos as &$partido) {
      $miPartido = Partidos::find($partido);
      $objectID = new \MongoDB\BSON\ObjectId($miPartido->_id);
      foreach ($editores as &$editor) {
        $arregloPartidosAsignados = $editor->partidosAsignados;
        if (($key2 = array_search($objectID, $arregloPartidosAsignados)) !== null){
          unset($arregloPartidosAsignados[$key2]);
          $nuevosPartidosAsignados = array_values($arregloPartidosAsignados);
          $editor->partidosAsignados = $nuevosPartidosAsignados;
          $editor->save();
        }
      }

      $miPartido->delete(); 
    }

    Fechas::destroy($id);

    return redirect('fixture');
  }

  public function nuevoPartido(){

    $IDFecha = request()->IDFecha;
    $fecha = Fechas::find($IDFecha);
    $arregloPartidos = $fecha->partidos;
    
    $equipoLocal = Equipos::where('nombre', request()->equipoLocal)->get()->first();
    $equipoVisita = Equipos::where('nombre', request()->equipoVisita)->get()->first();

    if (count($arregloPartidos) < 3 && $equipoLocal != null && $equipoVisita != null){
      $partido = new Partidos;

      $IDLocal = $equipoLocal->_id;
      $IDVisita = $equipoVisita->_id;
      
      
    	$partido->cancha = request()->cancha;
    	$partido->fecha = request()->fecha;
    	$partido->hora = request()->hora;
    	$partido->arbitro = request()->arbitro;
      $partido->equipoLocal = new \MongoDB\BSON\ObjectId($IDLocal);
      $partido->equipoVisitante = new \MongoDB\BSON\ObjectId($IDVisita);
      $partido->golesLocal = null;
      $partido->golesVisita = null;

      $partido->save();

      $IDPartido = new \MongoDB\BSON\ObjectId($partido->_id);
      array_push($arregloPartidos, $IDPartido);
      $fecha->partidos = $arregloPartidos;
      $fecha->save();
    }

    return redirect('fixture');
  }

  public function eliminarPartido($id){
    $objectID = new \MongoDB\BSON\ObjectId($id);
    
    $fecha = Fechas::find(request()->IDFecha);
    $arregloPartidos = $fecha->partidos;

    if (($key = array_search($objectID, $arregloPartidos)) !== null) {
      unset($arregloPartidos[$key]);
      $nuevoArreglo = array_values($arregloPartidos);

      // Actualizamos editores
      $editores = Usuarios::where('esEditor', true)->get();
      foreach ($editores as &$editor) {
        $arregloPartidosAsignados = $editor->partidosAsignados;
        if (($key2 = array_search($objectID, $arregloPartidosAsignados)) !== null){
          unset($arregloPartidosAsignados[$key2]);
          $nuevosPartidosAsignados = array_values($arregloPartidosAsignados);
          $editor->partidosAsignados = $nuevosPartidosAsignados;
          $editor->save();
        }
      }
    }

    $fecha->partidos = $nuevoArreglo;
    $fecha->save();

    Partidos::destroy($id);

    return redirect('fixture');
  }

  public function modificarPartido(){

    $nombreLocal = Equipos::where('nombre', request()->equipoLocal)->get()->first();
    $nombreVisita = Equipos::where('nombre', request()->equipoVisita)->get()->first();

    if ($nombreLocal != null && $nombreVisita != null){
      $equipoLocal = $nombreLocal->_id;
      $IDLocal = new \MongoDB\BSON\ObjectId($equipoLocal);
      $equipoVisitante = $nombreVisita->_id;
      $IDVisita = new \MongoDB\BSON\ObjectId($equipoVisitante);
      $IDPartido = request()->IDPartido;
      $partido = Partidos::find($IDPartido);
      $partido->cancha = request()->cancha;
      $partido->fecha = request()->fecha;
      $partido->hora = request()->hora;
      $partido->arbitro = request()->arbitro;
      $partido->equipoLocal = $IDLocal;
      $partido->equipoVisitante = $IDVisita;
      $partido->golesLocal = request()->golesLocal;
      $partido->golesVisita = request()->golesVisita;
      $partido->save();
     }

    return redirect('fixture');
  }

  public function asignarEditor(){
    $IDPartido = new \MongoDB\BSON\ObjectId(request()->IDPartido);
    $usuario = Usuarios::where('mail', request()->mailEditor)->get()->first();
    if ($usuario != null){
      if($usuario->esEditor == true){
        $arregloPartidos = $usuario->partidosAsignados;
        array_push($arregloPartidos, $IDPartido);
        $usuario->partidosAsignados = $arregloPartidos;
        $usuario->save();
      }
    }
    return redirect('fixture');

  }

}