<?php

namespace App\Http\Controllers;

use App\Fechas;
use App\Partidos;
use App\Equipos;
use App\Jugadores;

class JoinTablas {
  
  public function juntarFechasPartidos($fechas) {
    
    foreach ($fechas as &$fecha) {
    	$arreglo = $fecha->partidos;
    	
    	for($i = 0; $i<count($arreglo); $i++){
    		
    		$partido = Partidos::find($arreglo[$i]);
    		$partido->nombreEquipoLocal = Equipos::find($partido->equipoLocal)->nombre;
      		$partido->nombreEquipoVisitante = Equipos::find($partido->equipoVisitante)->nombre;

      		$arreglo[$i] = $partido;
      		
    	}
     	$fecha->partidos = $arreglo;
    }

    return $fechas;
  }

  public function juntarEquiposJugadores($equipos){
    foreach ($equipos as &$equipo) {
      $arreglo = $equipo->plantel;
      for($i = 0; $i<count($arreglo); $i++){
        $jugador = Jugadores::find($arreglo[$i]);
        $arreglo[$i] = $jugador;
      }
      $equipo->plantel = $arreglo;
    }
    return $equipos;
  }
}
?>