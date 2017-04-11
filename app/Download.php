<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = [
        'imagem', 'origem_id', 'titulo', 'descricao', 'arquivo', 'cmsuser_id', 'idioma_id',
    ];
}
