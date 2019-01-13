<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptionAbrangencia extends Model
{

    protected $table = "options_abrangencias";

    protected $fillable = [
        'id', 'on', 'listAll', 'height', 'cmsuser_id',
    ];
}

