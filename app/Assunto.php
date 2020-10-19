<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assunto extends Model
{
    protected $fillable = [
        'imagem', 'status', 'cmsuser_id',
    ];
}
