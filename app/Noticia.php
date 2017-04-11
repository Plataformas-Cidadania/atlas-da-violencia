<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $fillable = [
        'imagem', 'titulo', 'descricao', 'autor', 'fonte', 'link_font', 'cmsuser_id', 'idioma_id',
    ];
}
