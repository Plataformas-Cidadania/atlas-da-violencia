<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    protected $fillable = [
        'tema', 'tipo', 'imagem', 'position', 'tema_id', 'cmsuser_id',
    ];
}
