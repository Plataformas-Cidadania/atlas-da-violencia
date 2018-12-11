<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilterOptionAbrangencia extends Model
{
    protected $table = "filters_options_abrangencias";

    protected $fillable = [
        'title', 'option_abrangencia_id', 'cmsuser_id',
    ];
}

