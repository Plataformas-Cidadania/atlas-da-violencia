<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artigo extends Model
{
    protected $fillable = [
        'imagem', 'origem_id', 'data', 'titulo', 'descricao', 'autor', 'fonte_id', 'fonte', 'url', 'link', 'arquivo', 'legenda', 'cmsuser_id', 'idioma_sigla',
    ];
}
