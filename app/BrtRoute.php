<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrtRoute extends Model
{
    protected $table = 'brt';

    protected $fillable = [
        'codigo', 'linha', 'latitude', 'longitude', 'data_hora', 'velocidade', 'id_migracao_trajeto', 'sentido', 'trajeto', 'uf', 'cod_municipio', 'cmsuser_id'
    ];
}
