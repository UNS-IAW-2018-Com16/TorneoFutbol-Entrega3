<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Vistas principales

Route::get('fixture', 'FixtureController@index')->middleware('auth');

Route::get('equipos', 'EquipoController@index')->middleware('auth');

//Partidos

Route::get('fixture/formNuevoPartido/{id}', 'FixtureController@formNuevoPartido')->middleware('auth');

Route::post('fixture/nuevoPartido/{id}', 'FixtureController@nuevoPartido')->middleware('auth');

Route::get('fixture/formModificarPartido/{id}','FixtureController@formModificarPartido')->middleware('auth');

Route::post('fixture/modificarPartido/{id}','FixtureController@modificarPartido')->middleware('auth');

Route::post('fixture/eliminarPartido', 'FixtureController@eliminarPartido')->middleware('auth');

Route::get('fixture/formModificarEditores','FixtureController@formModificarEditores')->middleware('auth');

Route::post('fixture/modificarEditores','FixtureController@modificarEditores')->middleware('auth');

//Fechas

Route::post('fixture/agregarFecha', 'FixtureController@agregarFecha')->middleware('auth');

Route::post('fixture/eliminarFecha', 'FixtureController@eliminarFecha')->middleware('auth');

//Equipos

Route::post('equipos/nuevoEquipo', 'EquipoController@nuevoEquipo')->middleware('auth');

Route::post('equipos/eliminarEquipo', 'EquipoController@eliminarEquipo')->middleware('auth');

Route::get('equipos/formNuevoJugador/{id}', 'EquipoController@formNuevoJugador')->middleware('auth');

Route::post('equipos/nuevoJugador/{id}', 'EquipoController@nuevoJugador')->middleware('auth');

Route::get('equipos/formModificarJugador/{idEquipo}/{idJugador}', 'EquipoController@formModificarJugador')->middleware('auth');

Route::post('equipos/formModificarJugador/modificarJugador/{idEquipo}/{idJugador}', 'EquipoController@modificarJugador')->middleware('auth');

Route::post('equipos/eliminarJugador', 'EquipoController@eliminarJugador')->middleware('auth');

//Autenticacion

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'Auth\LoginController@index');
