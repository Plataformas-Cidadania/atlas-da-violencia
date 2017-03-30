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

//QUEM
Route::get('quem/', 'QuemController@detalhar');
Route::get('quem/{titulo}', 'QuemController@detalhar');
Route::get('quem/{origem_id}/{titulo}', 'QuemController@detalhar');


Route::get('acessibilidade/', 'AcessibilidadeController@detalhar');

Route::get('webdoor/{id}', 'WebdoorController@detalhar');

Route::get('contato/', 'ContatoController@listar');

//Route::get('renda/', 'RendaController@detalhar');
//Route::get('pagina/', 'PaginaController@detalhar');

Route::get('redirecionamento/{id}/{titulo}', 'LinkController@redirection');
Route::get('link/{id}/{titulo}', 'LinkController@detalhar');

//NOTÍCIAS
Route::get('noticias/', 'NoticiaController@listar');
Route::get('noticias/{titulo}', 'NoticiaController@listar');
Route::get('noticia/{id}', 'NoticiaController@detalhar');
Route::get('noticia/{id}/{titulo}', 'NoticiaController@detalhar');

//ARTIGOS
Route::get('artigos/{origem_id}', 'ArtigoController@listar');
Route::get('artigos/{origem_id}/{titulo}', 'ArtigoController@listar');
Route::get('artigos/{origem_id}/{titulo}/{autor_id}/{autor}', 'ArtigoController@listar');
Route::get('artigos/', 'ArtigoController@listar');
Route::get('artigos/{titulo}', 'ArtigoController@listar');
Route::get('artigo/{id}', 'ArtigoController@detalhar');
Route::get('artigo/{id}/{titulo}', 'ArtigoController@detalhar');
Route::post('busca-artigos/{origem_id}/{titulo}', 'ArtigoController@buscar');

//DOWNLOADS
Route::get('downloads/', 'DownloadController@listar');
Route::get('downloads/{titulo}', 'DownloadController@listar');

//VÍDEOS
Route::post('busca-videos/', 'VideoController@buscar');
Route::get('videos/', 'VideoController@listar');
Route::get('videos/{titulo}', 'VideoController@listar');

Route::get('indices', 'IndiceController@indice');

//SERIES//////////////////////////////////////////////////////////////////////////////////////

//Pgs
Route::get('series/', 'SerieController@listar');
Route::get('filtros/{id}/{titulo}', 'SerieController@filtros');
Route::post('dados-series/', 'SerieController@dataSeries');

//-------------------------AJAX-----------------------------------------------------------------------------------------

//Component
Route::post('territorios/{tipo}', 'SerieController@territorios');

//Component RangePeriodo nas pg filtros e series
Route::get('periodos/{id}', 'MapController@periodos');

//Component SeriesList na pg filtros
Route::post('listar-series-relacionadas/', 'SerieController@listarSeriesRelacionadas');
//Component SeriesList na pg series
Route::post('listar-series/', 'SerieController@listarSeries');

//Component filtroRegioes na pg filtros
Route::get('regioes/{id}', 'SerieController@regioes');//usado no component filtroRegioes na página de filtros

//Component pgSerie na pg dados-series
Route::get('valores-regiao/{id}/{max}/{regions}/{typeRegion}/{typeRegionSerie}', 'SerieController@valoresRegiaoUltimoPeriodo');

//Component map na pg dados-series
Route::get('regiao/{id}/{max}/{regions}/{typeRegion}/{typeRegionSerie}', 'MapController@valoresRegiaoUltimoPeriodoGeometry');

//Component chartLine na pg dados-series
Route::get('periodo/{id}/{min}/{max}/{regions}/{typeRegion}/{typeRegionSerie}', 'SerieController@valoresPeriodoRegioesSelecionadas');

Route::get('valores-inicial-final-regiao/{id}/{min}/{max}/{regions}', 'MapController@valoresInicialFinalRegiaoPorPeriodo');
//----------------------------------------------------------------------------------------------------------------------

//////////////////////////////////////////////////////////////////////

Route::get('teste/', 'SerieController@teste');
//Route::get('periodo/{id}/{min}/{max}', 'SerieController@valoresPeriodoPorRegiao');

Route::get('lang/{locale}', function ($locale) {
    //return App::getLocale();
    return Redirect::back();
});

//Route::get('series/{titulo}', 'SerieController@listar');
//Route::get('serie/', 'SerieController@detalhar');
//Route::get('serie/{id}', 'SerieController@detalhar');
//Route::get('serie/{id}/{titulo}', 'SerieController@detalhar');
//Route::get('filtro/', 'SerieController@filtro');
//Route::get('filtros/', 'SerieController@filtros');
//Route::get('filtro/{titulo}', 'SerieController@filtro');
//Route::post('enviar-contato', 'ContatoController@email');
//Route::get('map/', 'MapController@index');
//Route::get('map/{id}/{titulo}', 'MapController@index');
//Route::get('get-data/', 'MapController@getData');
//Route::get('regiao/{id}/{tipoValores}/{min}/{max}', 'MapController@valoresRegiaoPorPeriodoGeometry');
//Route::get('valores-regiao/{id}/{tipoValores}/{min}/{max}', 'MapController@valoresRegiaoPorPeriodo');
//Route::get('valores-series/{id}/{min}/{max}', 'MapController@valoresSeriesRegiaoPorPeriodo');
//Route::get('periodo/{id}/{min}/{max}', 'MapController@valoresPeriodoPorRegiao');
//Route::get('regioes/{id}', 'MapController@regioes');//usado no component filtroRegioes na página de filtros
//Route::get('valores/{id}/{min}/{max}', 'MapController@valores');


