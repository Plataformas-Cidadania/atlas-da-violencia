<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemaSerie extends Model
{
    protected $table = "temas_series";

    protected $fillable = [
        'tema_id', 'serie_id', 'cmsuser_id',
    ];
}
