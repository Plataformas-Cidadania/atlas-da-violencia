<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;

class GeoValor extends Model
{

    use PostgisTrait;

    protected $table = 'geovalores';

    protected $fillable = [
        'titulo', 'endereco', 'data', 'hora', 'ponto', 'serie_id', 'cmsuser_id',
    ];

    protected $postgisFields = [
        'ponto'
    ];

    protected $postgisTypes = [
        'ponto' => [
            'geomtype' => 'geometry',
            'srid' => 4674
        ]
    ];
}
