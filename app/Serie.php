<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = [
        'serie', 'periodicidade', 'serie_id', 'tema_id', 'fonte_id', 'cmsuser_id',
    ];
}
