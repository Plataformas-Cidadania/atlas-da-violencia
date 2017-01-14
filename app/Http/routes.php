<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


    Route::get('/', 'HomeController@index');


    Route::get('quem/', 'QuemController@detalhar');
    Route::get('quem/{titulo}', 'QuemController@detalhar');

    Route::get('acessibilidade/', 'AcessibilidadeController@detalhar');

    Route::get('webdoor/{id}', 'WebdoorController@detalhar');

    Route::get('contato/', 'ContatoController@listar');

    Route::get('renda/', 'RendaController@detalhar');
    Route::get('pagina/', 'PaginaController@detalhar');

    Route::get('noticias/', 'NoticiaController@listar');
    Route::get('noticias/{titulo}', 'NoticiaController@listar');
    Route::get('noticia/{id}', 'NoticiaController@detalhar');
    Route::get('noticia/{id}/{titulo}', 'NoticiaController@detalhar');

    Route::get('series/', 'SerieController@listar');
    Route::get('listar-series/', 'SerieController@listarSeries');
    Route::get('series/{titulo}', 'SerieController@listar');
    Route::get('serie/', 'SerieController@detalhar');
    Route::get('serie/{id}', 'SerieController@detalhar');
    Route::get('serie/{id}/{titulo}', 'SerieController@detalhar');
    Route::get('filtro/', 'SerieController@filtro');
    Route::get('filtro/{titulo}', 'SerieController@filtro');

    Route::post('enviar-contato', 'ContatoController@email');


    Route::get('map/', 'MapController@index');
    Route::get('get-data/', 'MapController@getData');

    Route::get('periodos/', 'MapController@periodos');
    Route::get('regiao/{min}/{max}', 'MapController@valoresRegiaoPorPeriodo');
    Route::get('valores-series/{min}/{max}', 'MapController@valoresSeriesRegiaoPorPeriodo');
    Route::get('periodo/{min}/{max}', 'MapController@valoresPeriodoPorRegiao');

    Route::get('indices', function(){
        //teste
        $indices = [8750, 1240, 2265, 348];
        return $indices;
    });
//});