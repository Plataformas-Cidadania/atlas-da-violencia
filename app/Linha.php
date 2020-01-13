<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Linha extends Model
{

    protected $fillable = [
        'imagem', 'icone', 'titulo', 'slug', 'transporte_id', 'cmsuser_id',
    ];
}

