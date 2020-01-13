<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Radar extends Model
{
    protected $table = "radares";
    protected $fillable = [
        'imagem', 'icone', 'titulo', 'tipo', 'slug', 'cmsuser_id',
    ];
}

