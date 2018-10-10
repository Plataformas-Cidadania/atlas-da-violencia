<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValorFiltro extends Model
{

    protected $table = 'valores_filtros';

    protected $fillable = [
        'titulo', 'filtro_id' , 'cmsuser_id',
    ];
}
