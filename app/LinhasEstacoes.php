<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinhasEstacoes extends Model
{

    protected $table = 'linhas_estacoes';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'linha_id', 'estacao_id',
    ];
}

