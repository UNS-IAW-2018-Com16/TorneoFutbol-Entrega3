<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Partidos extends Eloquent {

	public $timestamps = false;
   
    protected $collection = 'partidos';

}