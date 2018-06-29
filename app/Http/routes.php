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
Route::get('info123', function(){
     //return view('info');
});


Route::get('/', 'HomeController@index');
Route::post('/', 'HomeController@newsletter');

//QUEM
Route::get('quem/', 'QuemController@detalhar');
Route::get('quem/{titulo}', 'QuemController@detalhar');
Route::get('quem/{origem_id}/{titulo}', 'QuemController@detalhar');


Route::get('acessibilidade/', 'AcessibilidadeController@detalhar');

Route::get('webdoor/{id}', 'WebdoorController@detalhar');

Route::get('contato/', 'ContatoController@listar');

Route::get('renda/', 'RendaController@detalhar');
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
Route::get('downloads/{serie_id}/{titulo}', 'DownloadController@listar');
Route::get('download/{id}', 'DownloadController@detalhar');
Route::get('download/{id}/{titulo}', 'DownloadController@detalhar');

//VÍDEOS
Route::post('busca-videos/', 'VideoController@buscar');
Route::get('videos/', 'VideoController@listar');
Route::get('videos/{titulo}', 'VideoController@listar');

Route::get('indices', 'IndiceController@indice');

//SERIES//////////////////////////////////////////////////////////////////////////////////////

//Pgs
Route::get('series/', 'SerieController@listar');
Route::get('filtros/{id}/{titulo}', 'SerieController@filtros');
//Route::post('dados-series/', 'SerieController@dataSeries');
Route::get('dados-series/{serie_id}', 'SerieController@dataSeries');
Route::post('download-dados/', 'SerieController@downloadDados');

Route::get('antigo-filtros-series/{id}/{tema}', 'FiltrosController@index');
Route::get('antigo-filtros-series/', 'FiltrosController@index');

Route::get('filtros-series2/{id}/{tema}', 'FiltrosController@index');
Route::get('filtros-series2/', 'FiltrosController@index');

//-------------------------AJAX-----------------------------------------------------------------------------------------

//Component Temas em components/filtros/pgFiltros
Route::get('get-temas/{id}', 'FiltrosController@temas');
Route::get('get-indicadores/{tema_id}', 'FiltrosController@indicadores');
Route::get('get-abrangencias/{tema_id}', 'FiltrosController@abrangencias');
Route::post('get-series/', 'FiltrosController@series');

//Component
Route::post('territorios/', 'SerieController@territorios');

//Component RangePeriodo nas pg filtros e series
//Route::get('periodos/{id}', 'MapController@periodos');
Route::get('periodos/{id}/{abrangencia}', 'MapController@periodos');

Route::get('home-chart/{id}', 'SerieController@homeChart');

//Component Indicadores na pg filtros
Route::get('get-indicadores-series/{serie_id}', 'SerieController@getIndicadoresSeries');
//Component Abrangencia na pg filtros
Route::get('get-abrangencias-series/{serie_id}', 'SerieController@getAbrangenciasSeries');

//Component SeriesList na pg filtros
Route::post('listar-series-relacionadas/', 'SerieController@listarSeriesRelacionadas');
//Component SeriesList na pg series
Route::post('listar-series/', 'SerieController@listarSeries');

//Component filtroRegioes na pg filtros
//Route::get('regioes/{id}', 'SerieController@regioes');//usado no component filtroRegioes na página de filtros

//Component pgSerie na pg dados-series
Route::get('valores-regiao/{id}/{min}/{max}/{regions}/{abrangencia}', 'SerieController@valoresRegiaoPrimeiroUltimoPeriodo');
Route::get('get-regions/{abrangencia}', 'SerieController@getRegionsByAbrangencia');

//Component map na pg dados-series
Route::get('regiao/{id}/{periodo}/{regions}/{abrangencia}', 'MapController@valoresRegiaoPeriodoGeometry');

//Component chartLine na pg dados-series
Route::get('periodo/{id}/{min}/{max}/{regions}/{abrangencia}', 'SerieController@valoresPeriodoRegioesSelecionadas');

Route::get('valores-inicial-final-regiao/{id}/{min}/{max}/{regions}/{abrangencia}', 'MapController@valoresInicialFinalRegiaoPorPeriodo');
//----------------------------------------------------------------------------------------------------------------------

//////////////////////////////////////////////////////////////////////

Route::get('teste/', 'SerieController@teste');
Route::get('teste2/', 'SerieController@teste2');
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

Route::post('enviar-contato-serie/', 'ContatoSerieController@email');


Route::get('mapa-calor/', function () {
    return view('mapa-calor');
});
Route::get('new-maps/', function () {
    return view('new-maps');
    //return view('new-mapsORIGINAL');
});
Route::get('acidentes-transito/', function () {
    return view('transito');
});
Route::post('valores-transito/', 'TransitoController@valoresMapa');
Route::post('total-transito-territorio/', 'TransitoController@totalPorTerritorio');
Route::post('pontos-transito-territorio/', 'TransitoController@pontosPorTerritorio');
Route::post('pontos-transito-pais/', 'TransitoController@pontosPorPais');

Route::post('periodos-pontos/', 'TransitoController@periodosPontosAno');
Route::post('types/', 'TransitoController@types');
Route::post('types-accident/', 'TransitoController@typesAccident');
Route::post('genders/', 'TransitoController@genders');
Route::post('regions/', 'TransitoController@regions');
Route::post('default-regions/', 'TransitoController@defaultRegions');
Route::post('years/', 'TransitoController@years');
Route::post('months/', 'TransitoController@months');
Route::post('values-for-types/', 'TransitoController@valuesForTypes');
Route::post('values-for-gender/', 'TransitoController@valuesForGender');
Route::post('values-for-regions/', 'TransitoController@valuesForRegions');
Route::post('arrays-transito/', 'TransitoController@arraysTransito');

//////////NOVA PÁGINAS DE FILTROS/////////////////////////
Route::get('filtros-series/{id}/{tema}', 'FiltrosSeriesController@index');
Route::get('filtros-series/', 'FiltrosSeriesController@index');
//Route::post('get-temas', 'FiltrosSeriesController@temas');
Route::post('get-indicadores', 'FiltrosSeriesController@indicadores');
Route::post('get-abrangencias', 'FiltrosSeriesController@abrangencias');
Route::post('list-series', 'FiltrosSeriesController@series');
Route::post('territorios-serie-abrangencia', 'FiltrosSeriesController@territoriosSerieAbrangencia');
//////////////////////////////////////////////////////////
