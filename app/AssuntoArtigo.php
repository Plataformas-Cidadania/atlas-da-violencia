<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssuntoArtigo extends Model
{

    protected $table = 'assuntos_artigos';
    protected $primaryKey = 'assunto_id';

    protected $fillable = [
        'assunto_id', 'artigo_id',
    ];
}
