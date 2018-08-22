<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaTema extends Model
{
    protected $table = "idiomas_temas";

    protected $fillable = [
        'titulo', 'idioma_sigla', 'tema_id', 'cmsuser_id',
    ];
}
