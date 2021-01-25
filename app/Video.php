<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'imagem', 'titulo', 'descricao', 'link_video', 'tags', 'posicao', 'cmsuser_id', 'idioma_sigla', 'data', 'outros', 'destaque'
    ];
}
