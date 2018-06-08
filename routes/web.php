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

Route::get('fixture', 'FixtureController@index');

Route::get('equipos', 'EquipoController@index');

Route::get('editores', 'EditorController@index');

Route::post('nuevoPartido', 'FixtureController@nuevoPartido');

Route::post('nuevoEquipo', 'EquipoController@nuevoEquipo');

Route::post('nuevoEditor', 'EditorController@nuevoEditor');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
