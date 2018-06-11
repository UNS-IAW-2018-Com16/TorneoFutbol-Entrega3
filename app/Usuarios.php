<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Usuarios extends Eloquent {

	public $timestamps = false;

    protected $collection = 'usuarios';

}