<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected  $table = "consultas";
    protected $fillable = [
        'periodicidade_id', 'tema_id', 'unidade_id', 'arquivo', 'titulo', 'url', 'cmsuser_id',
    ];
}
