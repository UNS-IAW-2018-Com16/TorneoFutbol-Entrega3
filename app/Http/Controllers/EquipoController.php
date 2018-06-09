<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\JoinTablas;
use App\Equipos;

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

  	$image = request()->file('escudo');
  	if ($image != null){
	    $name = time().'.'.$image->getClientOriginalExtension();
	    $destinationPath = public_path('/images');
	    $image->move($destinationPath, $name);
	    $equipo->escudo = $name;
	}

  	$equipo->save();

  	return redirect('/equipos');
  }

  public function eliminarEquipo(){
  	
  }

}