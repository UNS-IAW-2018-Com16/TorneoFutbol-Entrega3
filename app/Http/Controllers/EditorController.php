<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Editores;

class EditorController extends Controller
{
  public function index(){
    return view('editores');
  }

  public function nuevoEditor(){
    dd(request()->all());
  }


}