<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favicon extends Model
{
    protected $fillable = [
        'imagem', 'titulo', 'cmsuser_id',
    ];
}