<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaLinha extends Model
{

    protected $table = "idiomas_linhas";

    protected $fillable = [
        'titulo', 'idioma_sigla', 'linha_id', 'cmsuser_id',
    ];
}
