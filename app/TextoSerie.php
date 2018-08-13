<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TextoSerie extends Model
{
    protected $table = "textos_series";

    protected $fillable = [
        'titulo', 'descricao', 'idioma_sigla', 'serie_id', 'cmsuser_id',
    ];
}
