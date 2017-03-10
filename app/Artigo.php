<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artigo extends Model
{
    protected $fillable = [
        'imagem', 'origem_id', 'titulo', 'descricao', 'autor', 'fonte', 'url', 'legenda', 'cmsuser_id',
    ];
}
