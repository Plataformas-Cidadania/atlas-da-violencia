<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaConsulta extends Model
{
    protected $table = "idiomas_consultas";

    protected $fillable = [
        'titulo', 'idioma_sigla', 'consulta_id', 'cmsuser_id',
    ];
}
