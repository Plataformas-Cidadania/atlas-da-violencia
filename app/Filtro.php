<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filtro extends Model
{
    protected $fillable = [
        'titulo', 'cmsuser_id',
    ];
}
