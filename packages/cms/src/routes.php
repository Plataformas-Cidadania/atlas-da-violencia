<?php



Route::group(['middleware' => 'cms'], function () {
    
    Route::get('/cms/login', 'Cms\Controllers\HomeController@telaLogin');
    Route::get('/cms/logout', 'Cms\Controllers\HomeController@logout');
    Route::post('/cms/login', 'Cms\Controllers\HomeController@login');

    Route::group(['middleware' => 'authcms:cms'], function () {
        
        Route::get('/cms', 'Cms\Controllers\HomeController@index');
        
        //INSTITUCIONAL
        Route::get('/cms/quemsomos', 'Cms\Controllers\QuemsomoController@index');
        Route::get('/cms/listar-quemsomos', 'Cms\Controllers\QuemsomoController@listar');
        Route::post('/cms/inserir-quemsomo', 'Cms\Controllers\QuemsomoController@inserir');
        Route::get('/cms/quemsomo/{id}', 'Cms\Controllers\QuemsomoController@detalhar');
        Route::post('/cms/alterar-quemsomo/{id}', 'Cms\Controllers\QuemsomoController@alterar');
        Route::get('/cms/excluir-quemsomo/{id}', 'Cms\Controllers\QuemsomoController@excluir');

        //WEBDOORS
        Route::get('/cms/webdoors', 'Cms\Controllers\WebdoorController@index');
        Route::get('/cms/listar-webdoors', 'Cms\Controllers\WebdoorController@listar');
        Route::post('/cms/inserir-webdoor', 'Cms\Controllers\WebdoorController@inserir');
        Route::get('/cms/webdoor/{id}', 'Cms\Controllers\WebdoorController@detalhar');
        Route::post('/cms/alterar-webdoor/{id}', 'Cms\Controllers\WebdoorController@alterar');
        Route::get('/cms/excluir-webdoor/{id}', 'Cms\Controllers\WebdoorController@excluir');

        //NOTICIAS
        Route::get('/cms/noticias', 'Cms\Controllers\NoticiaController@index');
        Route::get('/cms/listar-noticias', 'Cms\Controllers\NoticiaController@listar');
        Route::post('/cms/inserir-noticia', 'Cms\Controllers\NoticiaController@inserir');
        Route::get('/cms/noticia/{id}', 'Cms\Controllers\NoticiaController@detalhar');
        Route::post('/cms/alterar-noticia/{id}', 'Cms\Controllers\NoticiaController@alterar');
        Route::get('/cms/excluir-noticia/{id}', 'Cms\Controllers\NoticiaController@excluir');

        //ARTIGOS
        Route::get('/cms/artigos', 'Cms\Controllers\ArtigoController@index');
        Route::get('/cms/listar-artigos', 'Cms\Controllers\ArtigoController@listar');
        Route::post('/cms/inserir-artigo', 'Cms\Controllers\ArtigoController@inserir');
        Route::get('/cms/artigo/{id}', 'Cms\Controllers\ArtigoController@detalhar');
        Route::post('/cms/alterar-artigo/{id}', 'Cms\Controllers\ArtigoController@alterar');
        Route::get('/cms/excluir-artigo/{id}', 'Cms\Controllers\ArtigoController@excluir');
        
        //SERIES
        Route::get('/cms/series', 'Cms\Controllers\SerieController@index');
        Route::get('/cms/listar-series', 'Cms\Controllers\SerieController@listar');
        Route::post('/cms/inserir-serie', 'Cms\Controllers\SerieController@inserir');
        Route::get('/cms/serie/{id}', 'Cms\Controllers\SerieController@detalhar');
        Route::post('/cms/alterar-serie/{id}', 'Cms\Controllers\SerieController@alterar');
        Route::get('/cms/excluir-serie/{id}', 'Cms\Controllers\SerieController@excluir');

        Route::get('/cms/teste-excel', 'Cms\Controllers\SerieController@testeExcel');
        
        //VIDEOS
        Route::get('/cms/videos', 'Cms\Controllers\VideoController@index');
        Route::get('/cms/listar-videos', 'Cms\Controllers\VideoController@listar');
        Route::post('/cms/inserir-video', 'Cms\Controllers\VideoController@inserir');
        Route::get('/cms/video/{id}', 'Cms\Controllers\VideoController@detalhar');
        Route::post('/cms/alterar-video/{id}', 'Cms\Controllers\VideoController@alterar');
        Route::get('/cms/excluir-video/{id}', 'Cms\Controllers\VideoController@excluir');

        //LINKS
        Route::get('/cms/links', 'Cms\Controllers\LinkController@index');
        Route::get('/cms/listar-links', 'Cms\Controllers\LinkController@listar');
        Route::post('/cms/inserir-link', 'Cms\Controllers\LinkController@inserir');
        Route::get('/cms/link/{id}', 'Cms\Controllers\LinkController@detalhar');
        Route::post('/cms/alterar-link/{id}', 'Cms\Controllers\LinkController@alterar');
        Route::get('/cms/excluir-link/{id}', 'Cms\Controllers\LinkController@excluir');
        
        //INDICES
        Route::get('/cms/indices', 'Cms\Controllers\IndiceController@index');
        Route::get('/cms/listar-indices', 'Cms\Controllers\IndiceController@listar');
        Route::post('/cms/inserir-indice', 'Cms\Controllers\IndiceController@inserir');
        Route::get('/cms/indice/{id}', 'Cms\Controllers\IndiceController@detalhar');
        Route::post('/cms/alterar-indice/{id}', 'Cms\Controllers\IndiceController@alterar');
        Route::get('/cms/excluir-indice/{id}', 'Cms\Controllers\IndiceController@excluir');


        //Setting
        Route::get('/cms/setting/', 'Cms\Controllers\SettingController@detalhar');
        Route::post('/cms/alterar-setting/{id}', 'Cms\Controllers\SettingController@alterar');

        //User
        Route::get('/cms/usuarios', 'Cms\Controllers\CmsUserController@index');
        Route::get('/cms/listar-cmsusers', 'Cms\Controllers\CmsUserController@listar');
        Route::post('/cms/inserir-cmsuser', 'Cms\Controllers\CmsUserController@inserir');
        Route::get('/cms/usuario/{id}', 'Cms\Controllers\CmsUserController@detalhar');        
        Route::post('/cms/alterar-cmsuser/{id}', 'Cms\Controllers\CmsUserController@alterar');
        Route::get('/cms/perfil', 'Cms\Controllers\CmsUserController@perfil');
        Route::post('/cms/alterar-perfil', 'Cms\Controllers\CmsUserController@alterarPerfil');
        Route::get('/cms/excluir-cmsuser/{id}', 'Cms\Controllers\CmsUserController@excluir');
        
    });

});