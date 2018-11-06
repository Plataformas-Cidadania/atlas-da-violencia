<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaUnidade extends Model
{
    protected $table = "idiomas_unidades";

    protected $fillable = [
        'titulo', 'idioma_sigla', 'unidade_id', 'cmsuser_id',
    ];
}
