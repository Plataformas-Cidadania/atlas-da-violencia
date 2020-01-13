<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Integracao extends Model
{

    protected $table = 'integracoes';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'transporte_id', 'estacao_id',
    ];
}

