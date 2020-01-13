<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadaresRoute extends Model
{
    protected $table = 'dws_radar';

    protected $fillable = [
        'tipo', 'latitude', 'longitude', 'data_hora', 'velocidade', 'radar_id_fonte', 'km_rodovia', 'sigla_rodovia', 'sentido_duplo', 'sentido_todos', 'direcao_real', 'pais', 'uf', 'cod_municipio', 'status', 'cmsuser_id'
    ];
}
