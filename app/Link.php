<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'imagem', 'tipo', 'posicao', 'titulo', 'descricao', 'link', 'tags', 'cmsuser_id', 'idioma_sigla',
    ];
}
