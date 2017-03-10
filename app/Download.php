<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = [
        'imagem', 'titulo', 'descricao', 'arquivo', 'cmsuser_id',
    ];
}
