<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodicidade extends Model
{
    protected $fillable = [
        'titulo', 'cmsuser_id', 'idioma_sigla',
    ];
}
