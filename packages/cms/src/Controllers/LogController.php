<?php

namespace Cms\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(){


        return view('cms:logs.listar');
    }

    public function id_seq(){

        $tables = [
            'artigos', 'artworks', 'authors', 'cms_users', 'directives',
            'downloads', 'fontes', 'historias', 'hits', 'idiomas',
            'indicadores', 'indices', 'links', 'menu', 'noticias',
            'paginas', 'parceiros', 'periodicidades', 'printings',
            'quemsomos', 'series', 'settings', 'sub_temas', 'tags',
            'tags_series', 'temas', 'videos', 'visitantes', 'visitas',
            'webdoors'
        ];

        $rows = [];

        foreach($tables as $table){
            $id_seq = DB::select(DB::raw("select '$table' as table, last_value from ".$table."_id_seq"));
            $last_id = DB::table($table)->max('id');

            $row = [$id_seq[0]->table, $id_seq[0]->last_value, $last_id];

            array_push($rows, $row);
        }


        //return $rows;
        return view('cms::id_seq', ['rows' => $rows]);

    }
}
