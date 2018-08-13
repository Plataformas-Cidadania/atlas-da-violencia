<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    protected $fillable = [
        'titulo', 'tipo', 'cmsuser_id',
    ];
}

