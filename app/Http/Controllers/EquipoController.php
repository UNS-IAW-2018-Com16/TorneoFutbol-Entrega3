<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipos;
use App\Jugadores;
use App\Http\Controllers\JoinTablas;

class EquipoController extends Controller
{
  public function index(){
    $helper = new JoinTablas();
    $equipos = $helper->juntarEquiposJugadores(Equipos::all());

    return view('equipos', ['equipos' => $equipos ]);
  }

  public function nuevoEquipo(){
    $nombre = request()->equipoNuevo;

    $equipo = new Equipos;
    $equipo->nombre = $nombre;
    $equipo->plantel = [];

    $image = request()->file('escudo');
    if ($image != null){
      $name = $_FILES['escudo']['name'];
      $directorio = 'images/' . $nombre;
      if(!is_dir($directorio)){
        mkdir($directorio);
      }
      
      $destinationPath = public_path($directorio);
      $image->move($destinationPath, $name);
      $equipo->escudo = $name;
    }

    $equipo->save();

    \Session::flash('flash_message','El equipo ha sido cargado correctamente.');

    return redirect('/equipos');
  }

  public function eliminarEquipo(){
    $id = request()->IDEquipo;
    $equipo = Equipos::find($id);
    $nombreEquipo = $equipo->nombre;
    $plantel = $equipo->plantel;
    foreach($plantel as &$jugador){
      $miJugador = Jugadores::find($jugador);
      $miJugador->delete();
    }

    $equipo->delete();

    \Session::flash('flash_message','El equipo ha sido eliminado correctamente.');
    
    return redirect('equipos');
  }

  public function formNuevoJugador($id){

    $pieHabil = ["Derecha", "Izquierda", "Ambas"];
    $posiciones = ["Arquero", "Defensor", "Volante", "Delantero"];

    return view('nuevoJugador', ['IDEquipo' => $id, "pieHabil" => $pieHabil, "posiciones" => $posiciones]);
  }

  public function nuevoJugador($id){
    $equipo = Equipos::find($id);
    $nombreEquipo = $equipo->nombre;

    $jugador = new Jugadores;
    $jugador->nombre = request()->nombre;
    $jugador->apellido = request()->apellido;
    $jugador->fechaNacimiento = request()->fechaNacimiento;
    $jugador->peso = request()->peso;
    $jugador->altura = request()->altura;
    $jugador->edad = request()->edad;

    $image = request()->file('foto');
    if ($image != null){
      $name = $_FILES['foto']['name'];
      $directorio = 'images/' . $nombreEquipo . '/Plantel';
      if(!is_dir($directorio)){
        mkdir($directorio);
      }
      $destinationPath = public_path($directorio);
      $image->move($destinationPath, $name);
      $jugador->foto = $name;
    }

    $jugador->numeroCamiseta = request()->numeroCamiseta;
    $jugador->pieHabil = request()->pieHabil;
    $jugador->posicion = request()->posicion;

    $jugador->save();


    $arregloPlantel = $equipo->plantel;
    $IDJugador = new \MongoDB\BSON\ObjectId($jugador->_id);
    array_push($arregloPlantel, $IDJugador);
    $equipo->plantel = $arregloPlantel;
    $equipo->save();

    \Session::flash('flash_message','El jugador ha sido agregado correctamente.');

    return redirect('equipos');
  }

  public function eliminarJugador(){
    $id = request()->IDJugador;
    $objectID = new \MongoDB\BSON\ObjectId($id);
    
    $equipo = Equipos::find(request()->IDEquipo);
    $arregloPlantel = $equipo->plantel;

    if (($key = array_search($objectID, $arregloPlantel)) !== null) {
      unset($arregloPlantel[$key]);
      $nuevoArreglo = array_values($arregloPlantel);
    }

    $equipo->plantel = $nuevoArreglo;
    $equipo->save();

    Jugadores::destroy($id);

    \Session::flash('flash_message','El jugador ha sido eliminado correctamente.');

    return redirect('equipos');
  }

  public function formModificarJugador($idEquipo, $idJugador){
    $jugador = Jugadores::find($idJugador);
    $equipo = Equipos::find($idEquipo);
    $pieHabil = ["Derecha", "Izquierda", "Ambas"];
    $posiciones = ["Arquero", "Defensor", "Volante", "Delantero"];

    return view('modificarJugador', ['jugador' => $jugador, 'equipo' => $equipo, "pieHabil" => $pieHabil, "posiciones" => $posiciones]);
  }

  public function modificarJugador($idEquipo, $idJugador){
    
    $jugador = Jugadores::find($idJugador);

    $jugador->nombre = request()->nombre;
    $jugador->apellido = request()->apellido;
    $jugador->fechaNacimiento = request()->fechaNacimiento;
    $jugador->peso = request()->peso;
    $jugador->altura = request()->altura;
    $jugador->edad = request()->edad;
    $jugador->numeroCamiseta = request()->numeroCamiseta;
    $jugador->pieHabil = request()->pieHabil;
    $jugador->posicion = request()->posicion;

    $image = request()->file('foto');
    if ($image != null){
      $equipo = Equipos::find($idEquipo);
      $nombreEquipo = $equipo->nombre;
      $name = $_FILES['foto']['name'];
      $directorio = 'images/' . $nombreEquipo . '/Plantel';
      if(!is_dir($directorio)){
        mkdir($directorio);
      }
      $destinationPath = public_path($directorio);
      $image->move($destinationPath, $name);
      $jugador->foto = $name;
    }

    $jugador->save();

    \Session::flash('flash_message','El jugador ha sido modificado correctamente.');

    return redirect('equipos');
  }

}