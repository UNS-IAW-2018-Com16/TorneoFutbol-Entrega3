<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipos;

class EquipoController extends Controller
{
  public function index(){
    return view('equipos');
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