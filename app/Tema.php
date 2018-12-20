<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    protected $fillable = [
        'tema', 'tipo', 'imagem', 'tema_id', 'cmsuser_id',
    ];
}
