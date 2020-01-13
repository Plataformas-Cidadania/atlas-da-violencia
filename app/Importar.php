<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Importar extends Model
{
    protected $fillable = [
        'fonte_id', 'cmsuser_id', 'periodicidade_id', 'unidade', 'indicador', 'tipo_dados'
    ];
}
