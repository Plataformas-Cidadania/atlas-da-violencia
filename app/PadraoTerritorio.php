<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PadraoTerritorio extends Model
{
    protected $table = "padrao_territorios";

    protected $fillable = [
        'territorios', 'option_abrangencia_id', 'cmsuser_id',
    ];
}
