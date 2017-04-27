<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quemsomo extends Model
{
    protected $fillable = [
        'imagem', 'origem_id', 'titulo', 'descricao', 'tipo', 'cmsuser_id', 'idioma_sigla', 'posicao',
    ];
}
