<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaPeriodicidade extends Model
{
    protected $table = "idiomas_periodicidades";

    protected $fillable = [
        'titulo', 'idioma_sigla', 'periodicidade_id', 'cmsuser_id',
    ];
}
