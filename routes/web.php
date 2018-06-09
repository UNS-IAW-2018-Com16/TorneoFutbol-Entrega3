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

Route::get('fixture', 'FixtureController@index');

Route::get('equipos', 'EquipoController@index');

Route::get('editores', 'EditorController@index');

//Partidos

Route::post('fixture/nuevoPartido', 'FixtureController@nuevoPartido');

Route::post('fixture/eliminarPartido/{id}', 'FixtureController@eliminarPartido');

//Fechas

Route::post('fixture/agregarFecha', 'FixtureController@agregarFecha');

//Equipos

Route::post('equipos/nuevoEquipo', 'EquipoController@nuevoEquipo');

//Editores

Route::post('editores/nuevoEditor', 'EditorController@nuevoEditor');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
