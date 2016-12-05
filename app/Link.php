<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'imagem', 'posicao', 'titulo', 'descricao', 'link', 'tags', 'cmsuser_id',
    ];
}
