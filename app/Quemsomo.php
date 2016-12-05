<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quemsomo extends Model
{
    protected $fillable = [
        'imagem', 'titulo', 'descricao', 'tipo', 'cmsuser_id',
    ];
}
