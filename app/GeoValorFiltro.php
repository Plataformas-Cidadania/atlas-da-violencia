<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeoValorFiltro extends Model
{

    protected $table = 'geovalores_valores_filtros';

    protected $fillable = [
        'valor_filtro_id', 'geovalor_id', 'cmsuser_id',
    ];
}
