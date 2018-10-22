<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webindicador extends Model
{
    protected  $table = "webindicadores";
    protected $fillable = [
        'titulo', 'url', 'idioma_sigla', 'arquivo', 'cmsuser_id',
    ];
}
