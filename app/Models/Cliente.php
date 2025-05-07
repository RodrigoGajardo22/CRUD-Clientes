<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes_tables';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'dni'
    ];
}
