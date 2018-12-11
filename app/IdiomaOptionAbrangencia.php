<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaOptionAbrangencia extends Model
{
    protected $table = "idiomas_options_abrangencias";

    protected $fillable = [
        'title', 'plural', 'idioma_sigla', 'option_abrangencia_id', 'cmsuser_id',
    ];
}

