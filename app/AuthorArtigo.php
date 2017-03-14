<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorArtigo extends Model
{

    protected $table = 'author_artigo';
    protected $primaryKey = 'author_id';

    protected $fillable = [
        'author_id', 'artigo_id',
    ];
}
