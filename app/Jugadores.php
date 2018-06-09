<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Jugadores extends Eloquent {

    public $timestamps = false;
    
    protected $collection = 'jugadores';

}