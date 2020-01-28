<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusRoute extends Model
{
    protected $table = 'bus';

    protected $fillable = [
        'data_hora', 'ordem', 'linha', 'latitude', 'longitude', 'uf', 'cod_municipio', 'velocidade', 'cmsuser_id'
    ];
}
