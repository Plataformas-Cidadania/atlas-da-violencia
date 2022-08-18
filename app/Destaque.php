<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destaque extends Model
{
    protected $fillable = [
        'imagem', 'titulo', 'chamada', 'link', 'cmsuser_id', 'idioma_sigla',
    ];
}
