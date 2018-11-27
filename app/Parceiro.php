<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parceiro extends Model
{
    protected $fillable = [
        'imagem', 'titulo', 'descricao', 'arquivo', 'url', 'posicao', 'idioma_sigla',  'cmsuser_id',
    ];
}
