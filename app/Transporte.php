<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{

    protected $fillable = [
        'imagem', 'icone', 'titulo', 'tipo', 'slug', 'cmsuser_id',
    ];
}

