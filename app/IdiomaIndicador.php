<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaIndicador extends Model
{
    protected $table = "idiomas_indicadores";

    protected $fillable = [
        'titulo', 'idioma_sigla', 'indicador_id', 'cmsuser_id',
    ];
}
