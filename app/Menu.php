<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $fillable = [
        'imagem', 'title', 'url', 'status', 'posicao', 'idioma_sigla', 'accesskey', 'menu_id',
    ];
}
