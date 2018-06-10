<?php

namespace App\Http\Controllers;

use App\Fechas;
use App\Partidos;
use App\Equipos;
use App\Jugadores;

class JoinTablas {
  
  public function juntarFechasPartidos($fechas) {
    
    foreach ($fechas as &$fecha) {
      
    	$arregloPartidos = $fecha->partidos;

      foreach ($arregloPartidos as &$partido){
        
        $partido = Partidos::find($partido);
        $partido->nombreEquipoLocal = Equipos::find($partido->equipoLocal)->nombre;
        $partido->nombreEquipoVisitante = Equipos::find($partido->equipoVisitante)->nombre;

      }

      $fecha->partidos = $arregloPartidos;
    }

    return $fechas;
  }

  public function juntarEquiposJugadores($equipos){
    foreach ($equipos as &$equipo) {
      $arregloPlantel = $equipo->plantel;

      foreach ($arregloPlantel as &$jugador) {
        $jugador = Jugadores::find($jugador);
      }

      $equipo->plantel = $arregloPlantel;
    }
    return $equipos;
  }
}
?>