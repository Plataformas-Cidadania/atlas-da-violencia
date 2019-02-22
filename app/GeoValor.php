<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeoValor extends Model
{

    protected $table = 'geovalores';

    protected $fillable = [
        'endereco', 'ponto', 'data', 'hora', 'serie_id', 'cmsuser_id',
    ];
}
