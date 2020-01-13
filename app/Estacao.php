<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estacao extends Model
{
    protected $table = 'estacoes';

    protected $fillable = [
        'transporte_id', 'titulo', 'subtitulo', 'resumida', 'descricao', 'data_inicio', 'data_termino', 'endereco', 'telefone', 'ativo', 'status', 'latitude', 'longitude', 'uf', 'cod_municipio', 'cmsuser_id'
    ];
}
