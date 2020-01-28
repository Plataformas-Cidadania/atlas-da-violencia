<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaTransporte extends Model
{

    protected $table = "idiomas_transportes";

    protected $fillable = [
        'titulo', 'idioma_sigla', 'transporte_id', 'cmsuser_id',
    ];
}
