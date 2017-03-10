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

    Route::get('videos/', 'VideoController@listar');
    Route::get('videos/{titulo}', 'VideoController@listar');

    Route::get('series/', 'SerieController@listar');
    Route::post('listar-series/', 'SerieController@listarSeries');
    Route::post('listar-series-relacionadas/', 'SerieController@listarSeriesRelacionadas');
    Route::get('series/{titulo}', 'SerieController@listar');
    Route::get('serie/', 'SerieController@detalhar');
    Route::get('serie/{id}', 'SerieController@detalhar');
    Route::get('serie/{id}/{titulo}', 'SerieController@detalhar');
    Route::get('filtros/{id}/{titulo}', 'SerieController@filtros');
    Route::get('filtro/', 'SerieController@filtro');
    //Route::get('filtros/', 'SerieController@filtros');
    Route::get('filtro/{titulo}', 'SerieController@filtro');

    Route::post('enviar-contato', 'ContatoController@email');


    Route::get('map/', 'MapController@index');
    Route::get('map/{id}/{titulo}', 'MapController@index');
    Route::post('dados-series/', 'SerieController@dataSeries');
    Route::get('valores-regiao/{id}/{max}', 'SerieController@valoresRegiaoUltimoPeriodo');
    Route::get('periodo/{id}/{min}/{max}/{regions}', 'SerieController@valoresPeriodoRegioesSelecionadas');
    Route::get('periodo/{id}/{min}/{max}', 'SerieController@valoresPeriodoPorRegiao');
    //Route::get('get-data/', 'MapController@getData');

    Route::get('periodos/{id}', 'MapController@periodos');
    //Route::get('regiao/{id}/{tipoValores}/{min}/{max}', 'MapController@valoresRegiaoPorPeriodoGeometry');
    //Route::get('valores-regiao/{id}/{tipoValores}/{min}/{max}', 'MapController@valoresRegiaoPorPeriodo');
    Route::get('regiao/{id}/{max}', 'MapController@valoresRegiaoUltimoPeriodoGeometry');
    Route::get('valores-series/{id}/{min}/{max}', 'MapController@valoresSeriesRegiaoPorPeriodo');
    //Route::get('periodo/{id}/{min}/{max}', 'MapController@valoresPeriodoPorRegiao');
    Route::get('regioes/{id}', 'MapController@regioes');//usado no component filtroRegioes na página de filtros



    Route::get('valores-inicial-final-regiao/{id}/{min}/{max}', 'MapController@valoresInicialFinalRegiaoPorPeriodo');

    Route::get('indices', 'IndiceController@indice');


    Route::get('valores/{id}/{min}/{max}', 'MapController@valores');
//});