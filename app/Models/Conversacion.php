<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversacion extends Model
{
    protected $table = 'conversaciones';
    protected $fillable = ['usuario_id', 'mensaje', 'respuesta'];

    public function usuario(){
        return $this->belongsTo(User::class);
    }

}
