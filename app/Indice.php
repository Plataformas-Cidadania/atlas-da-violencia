<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indice extends Model
{
    protected $fillable = [
        'imagem', 'posicao', 'titulo', 'valor', 'status', 'cmsuser_id', 'idioma_sigla',
    ];
}
