<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresentationElement extends Model
{
    protected $table = 'presentations_elements';

    protected $fillable = [
        'type', 'chart_type', 'language', 'content', 'row',  'position',  'status', 'presentation_id', 'cmsuser_id',
    ];
}
