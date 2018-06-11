<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\JoinTablas;
use App\Equipos;
use App\Jugadores;

class EquipoController extends Controller
{
  public function index(){
    $helper = new JoinTablas();
    $equipos = $helper->juntarEquiposJugadores(Equipos::all());
    return view('equipos', ['equipos' => $equipos]);
  }

  public function nuevoEquipo(){
  	$nombre = request()->equipoNuevo;

  	$equipo = new Equipos;
  	$equipo->nombre = $nombre;
    $equipo->escudo = null;
    $equipo->plantel = [];

  	/*$image = request()->file('escudo');
  	if ($image != null){
	    $name = time().'.'.$image->getClientOriginalExtension();
	    $destinationPath = public_path('/images');
	    $image->move($destinationPath, $name);
	    $equipo->escudo = $name;
	}*/

  	$equipo->save();

  	return redirect('/equipos');
  }

  public function eliminarEquipo($id){
  	$equipo = Equipos::find($id);
    $plantel = $equipo->plantel;
    foreach($plantel as &$jugador){
      $miJugador = Jugadores::find($jugador);
      $miJugador->delete();
    }

    $equipo->delete();
    // HAY QUE ELIMINAR TODOS LOS JUGADORES DEL PLANTEL !!! ??????? 
    
    return redirect('equipos');
  }

  public function nuevoJugador(){
    
    $jugador = new Jugadores;
    $jugador->nombre = request()->nombre;
    $jugador->apellido = request()->apellido;
    $jugador->fechaNacimiento = request()->fechaNacimiento;
    $jugador->peso = request()->peso;
    $jugador->altura = request()->altura;
    $jugador->edad = request()->edad;
    $jugador->foto = request()->foto;
    $jugador->numeroCamiseta = request()->numeroCamiseta;
    $jugador->pieHabil = request()->pieHabil;
    $jugador->posicion = request()->posicion;

    $jugador->save();

    $IDEquipo = request()->IDEquipo;
    $equipo = Equipos::find($IDEquipo);
    $arregloPlantel = $equipo->plantel;
    $IDJugador = new \MongoDB\BSON\ObjectId($jugador->_id);
    array_push($arregloPlantel, $IDJugador);
    $equipo->plantel = $arregloPlantel;
    $equipo->save();

    return redirect('equipos');
  }

  public function eliminarJugador($id){
    $objectID = new \MongoDB\BSON\ObjectId($id);
    
    $equipo = Equipos::find(request()->IDEquipo);
    $arregloPlantel = $equipo->plantel;

    if (($key = array_search($objectID, $arregloPlantel)) !== null) {
      unset($arregloPlantel[$key]);
    }

    $equipo->plantel = $arregloPlantel;
    $equipo->save();

    Jugadores::destroy($id);

    return redirect('equipos');
  }

  public function modificarJugador(){
    $IDJugador = request()->IDJugador;
    
    $jugador = Jugadores::find($IDJugador);

    $jugador->nombre = request()->nombre;
    $jugador->apellido = request()->apellido;
    $jugador->fechaNacimiento = request()->fechaNacimiento;
    $jugador->peso = request()->peso;
    $jugador->altura = request()->altura;
    $jugador->edad = request()->edad;
    $jugador->foto = request()->foto;
    $jugador->numeroCamiseta = request()->numeroCamiseta;
    $jugador->pieHabil = request()->pieHabil;
    $jugador->posicion = request()->posicion;

    $jugador->save();

    return redirect('equipos');
  }

}