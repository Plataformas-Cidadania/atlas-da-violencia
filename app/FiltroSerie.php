<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FiltroSerie extends Model
{

    protected $table = 'filtros_series';

    protected $fillable = [
        'filtro_id', 'serie_id', 'cmsuser_id',
    ];
}
