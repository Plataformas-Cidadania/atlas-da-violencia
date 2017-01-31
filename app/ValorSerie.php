<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValorSerie extends Model
{

    protected $table = 'valores_series';

    protected $fillable = [
        'valor', 'periodo', 'uf', 'municipio', 'bairro', 'serie_id', 'cmsuser_id',
    ];
}
