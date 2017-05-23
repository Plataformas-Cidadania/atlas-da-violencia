<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fonte extends Model
{
    protected $fillable = [
        'titulo', 'cmsuser_id',
    ];
}
