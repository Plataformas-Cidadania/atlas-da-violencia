<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemaConsulta extends Model
{
    protected $table = "temas_consultas";

    protected $fillable = [
        'tema_id', 'consulta_id', 'cmsuser_id',
    ];
}
