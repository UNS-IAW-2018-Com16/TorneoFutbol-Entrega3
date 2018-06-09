<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Equipos extends Eloquent {

	public $timestamps = false;

    protected $collection = 'equipos';

}