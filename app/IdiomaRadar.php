<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaRadar extends Model
{

    protected $table = "idiomas_radares";

    protected $fillable = [
        'titulo', 'idioma_sigla', 'radar_id', 'cmsuser_id',
    ];
}
