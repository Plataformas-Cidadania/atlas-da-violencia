<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrtDataLine extends Model
{
    protected $table = 'brt_data_line';
    protected $fillable = [
        'id_transporte', 'linha', 'qtd', 'velocidade_minima', 'velocidade_maxima', 'velocidade_media', 'data', 'uf', 'cod_municipio', 'cmsuser_id'
    ];
}
