<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaApi extends Model
{
    protected $table = "idiomas_apis";

    protected $fillable = [
        'titulo', 'descricao', 'idioma_sigla', 'api_id', 'cmsuser_id',
    ];
}
