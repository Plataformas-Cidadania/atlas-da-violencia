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
//});