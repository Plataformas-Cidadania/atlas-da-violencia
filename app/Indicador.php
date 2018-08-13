<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    protected  $table = "indicadores";
    protected $fillable = [
        'titulo', 'cmsuser_id',
    ];
}
