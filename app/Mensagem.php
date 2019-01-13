<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table = "mensagens";

    protected $fillable = [
         'origem', 'nome', 'email', 'telefone', 'titulo', 'mensagem', 'status', 'cmsuser_id',
    ];
}
