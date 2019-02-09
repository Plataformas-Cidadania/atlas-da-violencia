<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $fillable = [
        'imagem', 'versao', 'tipo', 'url', 'resposta',  'cmsuser_id',
    ];
}
