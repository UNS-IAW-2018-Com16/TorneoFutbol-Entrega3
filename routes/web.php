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

Route::get('editores', 'EditorController@index')->middleware('auth');

//Partidos

Route::post('fixture/nuevoPartido', 'FixtureController@nuevoPartido')->middleware('auth');

Route::post('fixture/eliminarPartido/{id}', 'FixtureController@eliminarPartido')->middleware('auth');

Route::post('fixture/modificarPartido','FixtureController@modificarPartido')->middleware('auth');

Route::post('fixture/asignarEditor', 'FixtureController@asignarEditor')->middleware('auth');

//Fechas

Route::post('fixture/agregarFecha', 'FixtureController@agregarFecha')->middleware('auth');

Route::post('fixture/eliminarFecha/{id}', 'FixtureController@eliminarFecha')->middleware('auth');

//Equipos
Route::get('equipos/obtenerEquipo/{id}', 'EquipoController@obtenerEquipo')->middleware('auth');

Route::post('equipos/nuevoEquipo', 'EquipoController@nuevoEquipo')->middleware('auth');

Route::post('equipos/eliminarEquipo', 'EquipoController@eliminarEquipo')->middleware('auth');

Route::post('equipos/nuevoJugador', 'EquipoController@nuevoJugador')->middleware('auth');

Route::post('equipos/eliminarJugador', 'EquipoController@eliminarJugador')->middleware('auth');

Route::post('equipos/modificarJugador', 'EquipoController@modificarJugador')->middleware('auth');


//Editores

Route::post('editores/nuevoEditor', 'EditorController@nuevoEditor')->middleware('auth');

Route::post('editores/eliminarEditor', 'EditorController@eliminarEditor')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'Auth\LoginController@index');
