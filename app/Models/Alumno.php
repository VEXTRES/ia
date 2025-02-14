<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumno';
    

    protected $fillable = ['name','email','fecha_nacimiento'];


}
