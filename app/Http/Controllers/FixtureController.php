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
    \Session::flash('flash_message','La fecha ha sido creada correctamente.');
    return redirect('fixture');
  }

  public function eliminarFecha(){
    $fecha = Fechas::find(request()->IDFecha);

    $partidos = $fecha->partidos;
    $editores = Usuarios::where('esEditor', true)->get();
    foreach ($partidos as &$partido) {
      $miPartido = Partidos::find($partido);
      $objectID = new \MongoDB\BSON\ObjectId($miPartido->_id);
      foreach ($editores as &$editor) {
        $arregloPartidosAsignados = $editor->partidosAsignados;
        if (($key2 = array_search($objectID, $arregloPartidosAsignados)) !== false){
          unset($arregloPartidosAsignados[$key2]);
          $nuevosPartidosAsignados = array_values($arregloPartidosAsignados);
          $editor->partidosAsignados = $nuevosPartidosAsignados;
          $editor->save();
        }
      }

      $miPartido->delete(); 
    }

    Fechas::destroy(request()->IDFecha);

    \Session::flash('flash_message','La fecha ha sido eliminada correctamente.');

    return redirect('fixture');
  }

  public function formNuevoPartido($id){
    $equipos = Equipos::all();
    $editores = Usuarios::where('esEditor', true)->get();

    return view('nuevoPartido', ['IDFecha' => $id, 'equipos' => $equipos, 'editores' => $editores]);
  }

    public function nuevoPartido($id){
    $fecha = Fechas::find($id);
    $arregloPartidos = $fecha->partidos;
    
    $equipoLocal = Equipos::where('nombre', request()->equipoLocal)->get()->first();
    $equipoVisita = Equipos::where('nombre', request()->equipoVisita)->get()->first();
    $editor = Usuarios::where('nombre', request()->editor)->get()->first();
    if (count($arregloPartidos) < 3){
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

      if ($editor != null){
        $partidosAsignados = $editor->partidosAsignados;
        $key = array_search($IDPartido, $partidosAsignados); 
        if ($key === false){
          array_push($partidosAsignados, $IDPartido);
          $editor->partidosAsignados = $partidosAsignados;
          $editor->save();
        }
      }

      \Session::flash('flash_message','El partido ha sido agregado correctamente.');

    } else {
      \Session::flash('flash_message_error','Se puede agregar como maximo 3 partidos por fecha.');
    }

    return redirect('fixture');
  }


  public function eliminarPartido(){
    $objectID = new \MongoDB\BSON\ObjectId(request()->IDPartido);
    
    $fecha = Fechas::find(request()->IDFecha);
    $arregloPartidos = $fecha->partidos;

    if (($key = array_search($objectID, $arregloPartidos)) !== null) {
      unset($arregloPartidos[$key]);
      $nuevoArreglo = array_values($arregloPartidos);

      // Actualizamos editores
      $editores = Usuarios::where('esEditor', true)->get();
      foreach ($editores as &$editor) {
        $arregloPartidosAsignados = $editor->partidosAsignados;
        if (($key2 = array_search($objectID, $arregloPartidosAsignados)) !== false){
          unset($arregloPartidosAsignados[$key2]);
          $nuevosPartidosAsignados = array_values($arregloPartidosAsignados);
          $editor->partidosAsignados = $nuevosPartidosAsignados;
          $editor->save();
        }
      }
    }

    $fecha->partidos = $nuevoArreglo;
    $fecha->save();

    Partidos::destroy(request()->IDPartido);

    \Session::flash('flash_message','El partido ha sido eliminado correctamente.');

    return redirect('fixture');
  }

  public function formModificarPartido($id){
    $partido = Partidos::find($id);
    $equipos = Equipos::all();
    $editores = Usuarios::where('esEditor', true)->get();

    $IDPartido = new \MongoDB\BSON\ObjectId($id);
    $nombreEditor = null;
    foreach ($editores as &$editor) {
      $partidosAsignados = $editor->partidosAsignados;
      foreach ($partidosAsignados as &$partidoActual) {
        if($partidoActual == $IDPartido){
          $nombreEditor = $editor->nombre;
        }
      }
    }


    return view('modificarPartido', ['equipos' => $equipos, 'partido' => $partido, 'editores' => $editores, 'editorActual' => $nombreEditor]);
  }

  public function modificarPartido($id){
    $nombreLocal = Equipos::where('nombre', request()->equipoLocal)->get()->first();
    $nombreVisita = Equipos::where('nombre', request()->equipoVisita)->get()->first();

    $equipoLocal = $nombreLocal->_id;
    $IDLocal = new \MongoDB\BSON\ObjectId($equipoLocal);
    $equipoVisitante = $nombreVisita->_id;
    $IDVisita = new \MongoDB\BSON\ObjectId($equipoVisitante);
    
    $partido = Partidos::find($id);
    $partido->cancha = request()->cancha;
    $partido->fecha = request()->fecha;
    $partido->hora = request()->hora;
    $partido->arbitro = request()->arbitro;

    $partido->equipoLocal = $IDLocal;
    $partido->equipoVisitante = $IDVisita;

    $partido->golesLocal = request()->golesLocal;
    $partido->golesVisita = request()->golesVisita;

    $partido->save();
    $IDPartido = new \MongoDB\BSON\ObjectId($id);

    $editores = Usuarios::where('esEditor', true)->get();
    foreach ($editores as &$editor) {
      if ($editor->nombre != request()->editor){
        $arregloPartidosAsignados = $editor->partidosAsignados;
        if (($key2 = array_search($IDPartido, $arregloPartidosAsignados)) !== false){
          unset($arregloPartidosAsignados[$key2]);
          $nuevosPartidosAsignados = array_values($arregloPartidosAsignados);
          $editor->partidosAsignados = $nuevosPartidosAsignados;
          $editor->save();
        }
      }
    }

    $editor = Usuarios::where('nombre', request()->editor)->get()->first();

    if ($editor != null){
        $partidosAsignados = $editor->partidosAsignados;
        $key = array_search($IDPartido, $partidosAsignados); 
        if ($key === false){
            array_push($partidosAsignados, $IDPartido);
            $editor->partidosAsignados = $partidosAsignados;
            $editor->save();
        }  
    }

    \Session::flash('flash_message','El partido ha sido modificado correctamente.');

    return redirect('fixture');
  }

  public function formModificarEditores(){
    $usuarios = Usuarios::all();

    return view('editores', ['usuarios' => $usuarios]);
  }

  public function modificarEditores(){
    $usuarios = Usuarios::all();
    foreach ($usuarios as &$usuario){
      if(isset($_POST[$usuario->_id])){
        $usuario->esEditor = true;
        $usuario->save();
      } else {
        $usuario->esEditor = false;
        $usuario->partidosAsignados = [];
        $usuario->save();
      }
    }
    \Session::flash('flash_message','La lista de editores ha sido actualizada correctamente.');
    return redirect('fixture');
  }

}