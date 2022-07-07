<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = [
        'fonte_id', 'cmsuser_id', 'periodicidade_id', 'unidade', 'indicador', 'tipo_dados', 'arquivo', 'arquivo_metadados', 'status'
    ];
}
