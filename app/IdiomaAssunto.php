<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdiomaAssunto extends Model
{
    protected $table = "idiomas_assuntos";

    protected $fillable = [
        'titulo', 'idioma_sigla', 'assunto', 'assunto_id', 'cmsuser_id',
    ];
}
