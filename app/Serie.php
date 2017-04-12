<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = [
        'titulo', 'descricao', 'serie_id', 'tema_id', 'fonte_id', 'cmsuser_id', 'idioma_sigla', 'periodicidade_id', 'unidade', 'indicador', 'abrangencia',
    ];
}
