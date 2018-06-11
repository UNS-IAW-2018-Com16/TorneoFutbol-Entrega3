<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuarios;

class EditorController extends Controller
{
  public function index(){
    return view('editores');
  }

  public function nuevoEditor(){
    $usuario = Usuarios::where('mail', request()->mailEditor)->get()->first();
    if ($usuario != null){
    	$usuario->esEditor = true;
    	$usuario->save();
    }
    return redirect('editores');
  }

  public function eliminarEditor(){
  	$usuario = Usuarios::where('mail', request()->mailEditor)->get()->first();
  	if ($usuario != null){
  		$usuario->esEditor = false;
  		$usuario->save();
  	}
    return redirect('editores');
  }


}