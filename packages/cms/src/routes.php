<?php



Route::group(['middleware' => 'cms'], function () {
    
    Route::get('/cms/login', 'Cms\Controllers\HomeController@telaLogin');
    Route::get('/cms/logout', 'Cms\Controllers\HomeController@logout');
    Route::post('/cms/login', 'Cms\Controllers\HomeController@login');

    Route::group(['middleware' => 'authcms:cms'], function () {
        
        Route::get('/cms', 'Cms\Controllers\HomeController@index');
        
        //INSTITUCIONAL
        Route::get('/cms/quemsomos', 'Cms\Controllers\QuemsomoController@index');
        Route::get('/cms/quemsomos/{tipo_id}', 'Cms\Controllers\QuemsomoController@index');
        Route::get('/cms/quemsomos/{tipo_id}/{titulo}', 'Cms\Controllers\QuemsomoController@index');
        Route::get('/cms/listar-quemsomos', 'Cms\Controllers\QuemsomoController@listar');
        Route::post('/cms/inserir-quemsomo', 'Cms\Controllers\QuemsomoController@inserir');
        Route::get('/cms/quemsomo/{id}', 'Cms\Controllers\QuemsomoController@detalhar');
        Route::post('/cms/alterar-quemsomo/{id}', 'Cms\Controllers\QuemsomoController@alterar');
        Route::get('/cms/excluir-quemsomo/{id}', 'Cms\Controllers\QuemsomoController@excluir');

        //MENSAGEM
        Route::get('/cms/mensagens', 'Cms\Controllers\MensagemController@index');
        Route::get('/cms/mensagens/{origem}', 'Cms\Controllers\MensagemController@index');
        Route::get('/cms/listar-mensagens', 'Cms\Controllers\MensagemController@listar');
        Route::post('/cms/inserir-mensagem', 'Cms\Controllers\MensagemController@inserir');
        Route::get('/cms/mensagem/{id}', 'Cms\Controllers\MensagemController@detalhar');
        Route::post('/cms/alterar-mensagem/{id}', 'Cms\Controllers\MensagemController@alterar');
        Route::get('/cms/excluir-mensagem/{id}', 'Cms\Controllers\MensagemController@excluir');
        Route::get('/cms/status-mensagem/{id}', 'Cms\Controllers\MensagemController@status');

        //WEBDOORS
        Route::get('/cms/webdoors', 'Cms\Controllers\WebdoorController@index');
        Route::get('/cms/listar-webdoors', 'Cms\Controllers\WebdoorController@listar');
        Route::post('/cms/inserir-webdoor', 'Cms\Controllers\WebdoorController@inserir');
        Route::get('/cms/webdoor/{id}', 'Cms\Controllers\WebdoorController@detalhar');
        Route::post('/cms/alterar-webdoor/{id}', 'Cms\Controllers\WebdoorController@alterar');
        Route::get('/cms/excluir-webdoor/{id}', 'Cms\Controllers\WebdoorController@excluir');

        //DOWNLOADS
        Route::get('/cms/downloads', 'Cms\Controllers\DownloadController@index');
        Route::get('/cms/downloads/{origem}/{origem_id}', 'Cms\Controllers\DownloadController@index');
        Route::get('/cms/listar-downloads', 'Cms\Controllers\DownloadController@listar');
        Route::post('/cms/inserir-download', 'Cms\Controllers\DownloadController@inserir');
        Route::get('/cms/download/{id}', 'Cms\Controllers\DownloadController@detalhar');
        Route::post('/cms/alterar-download/{id}', 'Cms\Controllers\DownloadController@alterar');
        Route::get('/cms/excluir-download/{id}', 'Cms\Controllers\DownloadController@excluir');

        //NOTICIAS
        Route::get('/cms/noticias', 'Cms\Controllers\NoticiaController@index');
        Route::get('/cms/listar-noticias', 'Cms\Controllers\NoticiaController@listar');
        Route::post('/cms/inserir-noticia', 'Cms\Controllers\NoticiaController@inserir');
        Route::get('/cms/noticia/{id}', 'Cms\Controllers\NoticiaController@detalhar');
        Route::post('/cms/alterar-noticia/{id}', 'Cms\Controllers\NoticiaController@alterar');
        Route::get('/cms/excluir-noticia/{id}', 'Cms\Controllers\NoticiaController@excluir');

        //MENUS
        Route::get('/cms/menus', 'Cms\Controllers\MenuController@index');
        Route::get('/cms/menus/{origem_id}/{origem_titulo}', 'Cms\Controllers\MenuController@index');
        Route::get('/cms/listar-menus', 'Cms\Controllers\MenuController@listar');
        Route::post('/cms/inserir-menu', 'Cms\Controllers\MenuController@inserir');
        Route::get('/cms/menu/{id}', 'Cms\Controllers\MenuController@detalhar');
        Route::post('/cms/alterar-menu/{id}', 'Cms\Controllers\MenuController@alterar');
        Route::get('/cms/excluir-menu/{id}', 'Cms\Controllers\MenuController@excluir');
        Route::get('/cms/status-menu/{id}', 'Cms\Controllers\MenuController@status');
        Route::get('/cms/positionUp-menu/{id}', 'Cms\Controllers\MenuController@positionUp');
        Route::get('/cms/positionDown-menu/{id}', 'Cms\Controllers\MenuController@positionDown');

        //ARTIGOS
        Route::get('/cms/artigos', 'Cms\Controllers\ArtigoController@index');
        Route::get('/cms/listar-artigos', 'Cms\Controllers\ArtigoController@listar');
        Route::post('/cms/inserir-artigo', 'Cms\Controllers\ArtigoController@inserir');
        Route::get('/cms/artigo/{id}', 'Cms\Controllers\ArtigoController@detalhar');
        Route::post('/cms/alterar-artigo/{id}', 'Cms\Controllers\ArtigoController@alterar');
        Route::get('/cms/excluir-artigo/{id}', 'Cms\Controllers\ArtigoController@excluir');

        //PRESENTATION
        Route::get('/cms/presentations', 'Cms\Controllers\PresentationController@index');
        Route::get('/cms/listar-presentations', 'Cms\Controllers\PresentationController@listar');
        Route::post('/cms/inserir-presentation', 'Cms\Controllers\PresentationController@inserir');
        Route::get('/cms/presentation/{id}', 'Cms\Controllers\PresentationController@detalhar');
        Route::post('/cms/alterar-presentation/{id}', 'Cms\Controllers\PresentationController@alterar');
        Route::get('/cms/excluir-presentation/{id}', 'Cms\Controllers\PresentationController@excluir');

        //PRESENTATION ELEMENT
        Route::get('/cms/presentation-elements/{presentation_id}', 'Cms\Controllers\PresentationElementController@index');
        Route::get('/cms/listar-presentation-elements', 'Cms\Controllers\PresentationElementController@listar');
        Route::post('/cms/inserir-presentation-element', 'Cms\Controllers\PresentationElementController@inserir');
        Route::get('/cms/presentation-element/{id}', 'Cms\Controllers\PresentationElementController@detalhar');
        Route::post('/cms/alterar-presentation-element/{id}', 'Cms\Controllers\PresentationElementController@alterar');
        Route::get('/cms/excluir-presentation-element/{id}', 'Cms\Controllers\PresentationElementController@excluir');
        Route::get('/cms/status-presentation-element/{id}', 'Cms\Controllers\PresentationElementController@status');
        
        //SERIES
        Route::get('/cms/series', 'Cms\Controllers\SerieController@index');
        Route::get('/cms/listar-series', 'Cms\Controllers\SerieController@listar');
        Route::post('/cms/inserir-serie', 'Cms\Controllers\SerieController@inserir');
        Route::get('/cms/serie/{id}', 'Cms\Controllers\SerieController@detalhar');
        Route::post('/cms/alterar-serie/{id}', 'Cms\Controllers\SerieController@alterar');
        Route::get('/cms/excluir-serie/{id}', 'Cms\Controllers\SerieController@excluir');
        Route::get('/cms/importar-serie/{id}', 'Cms\Controllers\SerieController@viewImportar');
        Route::post('/cms/importar-serie', 'Cms\Controllers\SerieController@importar');
        Route::get('/cms/importar-varias-series', 'Cms\Controllers\SerieController@viewImportarVarias');
        Route::get('/cms/valores-filtros-serie/{id}', 'Cms\Controllers\SerieController@valoresFiltrosSerie');
        Route::get('/cms/atualizar-views-materializadas-pontos/', 'Cms\Controllers\SerieController@atualizarViewsMaterializadasPontos');
        Route::get('/cms/status-serie/{id}', 'Cms\Controllers\SerieController@status');


        //VALORES SERIES
        Route::get('/cms/valores-serie/{serie_id}', 'Cms\Controllers\ValoresSerieController@index');
        Route::get('/cms/limpar-valores-serie/{serie_id}/{abrangencia}/{tipo_dados}/{ano_pontos}', 'Cms\Controllers\ValoresSerieController@limparValoresSerie');

        //TEXTOS SERIES
        Route::get('/cms/textos-series/{serie_id}', 'Cms\Controllers\TextoSerieController@index');
        Route::get('/cms/listar-textos-series', 'Cms\Controllers\TextoSerieController@listar');
        Route::post('/cms/inserir-texto-serie', 'Cms\Controllers\TextoSerieController@inserir');
        Route::get('/cms/texto-serie/{id}', 'Cms\Controllers\TextoSerieController@detalhar');
        Route::post('/cms/alterar-texto-serie/{id}', 'Cms\Controllers\TextoSerieController@alterar');
        Route::get('/cms/excluir-texto-serie/{id}', 'Cms\Controllers\TextoSerieController@excluir');

        //IDIOMAS TEMAS
        Route::get('/cms/idiomas-temas/{tema_id}', 'Cms\Controllers\IdiomaTemaController@index');
        Route::get('/cms/listar-idiomas-temas', 'Cms\Controllers\IdiomaTemaController@listar');
        Route::post('/cms/inserir-idioma-tema', 'Cms\Controllers\IdiomaTemaController@inserir');
        Route::get('/cms/idioma-tema/{id}', 'Cms\Controllers\IdiomaTemaController@detalhar');
        Route::post('/cms/alterar-idioma-tema/{id}', 'Cms\Controllers\IdiomaTemaController@alterar');
        Route::get('/cms/excluir-idioma-tema/{id}', 'Cms\Controllers\IdiomaTemaController@excluir');

        //IDIOMAS UNIDADES
        Route::get('/cms/idiomas-unidades/{unidade_id}', 'Cms\Controllers\IdiomaUnidadeController@index');
        Route::get('/cms/listar-idiomas-unidades', 'Cms\Controllers\IdiomaUnidadeController@listar');
        Route::post('/cms/inserir-idioma-unidade', 'Cms\Controllers\IdiomaUnidadeController@inserir');
        Route::get('/cms/idioma-unidade/{id}', 'Cms\Controllers\IdiomaUnidadeController@detalhar');
        Route::post('/cms/alterar-idioma-unidade/{id}', 'Cms\Controllers\IdiomaUnidadeController@alterar');
        Route::get('/cms/excluir-idioma-unidade/{id}', 'Cms\Controllers\IdiomaUnidadeController@excluir');

        //IDIOMAS APIS
        Route::get('/cms/idiomas-apis/{api_id}', 'Cms\Controllers\IdiomaApiController@index');
        Route::get('/cms/listar-idiomas-apis', 'Cms\Controllers\IdiomaApiController@listar');
        Route::post('/cms/inserir-idioma-api', 'Cms\Controllers\IdiomaApiController@inserir');
        Route::get('/cms/idioma-api/{id}', 'Cms\Controllers\IdiomaApiController@detalhar');
        Route::post('/cms/alterar-idioma-api/{id}', 'Cms\Controllers\IdiomaApiController@alterar');
        Route::get('/cms/excluir-idioma-api/{id}', 'Cms\Controllers\IdiomaApiController@excluir');

        //IDIOMAS OPTIOSN ABRANGENCIAS
        Route::get('/cms/idiomas-options-abrangencias/{option_abrangencia_id}', 'Cms\Controllers\IdiomaOptionAbrangenciaController@index');
        Route::get('/cms/listar-idiomas-options-abrangencias', 'Cms\Controllers\IdiomaOptionAbrangenciaController@listar');
        Route::post('/cms/inserir-idioma-option-abrangencia', 'Cms\Controllers\IdiomaOptionAbrangenciaController@inserir');
        Route::get('/cms/idioma-option-abrangencia/{id}', 'Cms\Controllers\IdiomaOptionAbrangenciaController@detalhar');
        Route::post('/cms/alterar-idioma-option-abrangencia/{id}', 'Cms\Controllers\IdiomaOptionAbrangenciaController@alterar');
        Route::get('/cms/excluir-idioma-option-abrangencia/{id}', 'Cms\Controllers\IdiomaOptionAbrangenciaController@excluir');

        //IDIOMAS TRANSPORTES
        Route::get('/cms/idiomas-transportes/{transporte_id}', 'Cms\Controllers\IdiomaTransporteController@index');
        Route::get('/cms/listar-idiomas-transportes', 'Cms\Controllers\IdiomaTransporteController@listar');
        Route::post('/cms/inserir-idioma-transporte', 'Cms\Controllers\IdiomaTransporteController@inserir');
        Route::get('/cms/idioma-transporte/{id}', 'Cms\Controllers\IdiomaTransporteController@detalhar');
        Route::post('/cms/alterar-idioma-transporte/{id}', 'Cms\Controllers\IdiomaTransporteController@alterar');
        Route::get('/cms/excluir-idioma-transporte/{id}', 'Cms\Controllers\IdiomaTransporteController@excluir');

        //IDIOMAS RADARES
        Route::get('/cms/idiomas-radares/{radar_id}', 'Cms\Controllers\IdiomaRadarController@index');
        Route::get('/cms/listar-idiomas-radares', 'Cms\Controllers\IdiomaRadarController@listar');
        Route::post('/cms/inserir-idioma-radar', 'Cms\Controllers\IdiomaRadarController@inserir');
        Route::get('/cms/idioma-radar/{id}', 'Cms\Controllers\IdiomaRadarController@detalhar');
        Route::post('/cms/alterar-idioma-radar/{id}', 'Cms\Controllers\IdiomaRadarController@alterar');
        Route::get('/cms/excluir-idioma-radar/{id}', 'Cms\Controllers\IdiomaRadarController@excluir');

        //IDIOMAS LINHAS
        Route::get('/cms/idiomas-linhas/{linha_id}', 'Cms\Controllers\IdiomaLinhaController@index');
        Route::get('/cms/listar-idiomas-linhas', 'Cms\Controllers\IdiomaLinhaController@listar');
        Route::post('/cms/inserir-idioma-linha', 'Cms\Controllers\IdiomaLinhaController@inserir');
        Route::get('/cms/idioma-linha/{id}', 'Cms\Controllers\IdiomaLinhaController@detalhar');
        Route::post('/cms/alterar-idioma-linha/{id}', 'Cms\Controllers\IdiomaLinhaController@alterar');
        Route::get('/cms/excluir-idioma-linha/{id}', 'Cms\Controllers\IdiomaLinhaController@excluir');

        //FILTERS OPTIOSN ABRANGENCIAS
        Route::get('/cms/filters-options-abrangencias/{option_abrangencia_id}', 'Cms\Controllers\FilterOptionAbrangenciaController@index');
        Route::get('/cms/listar-filters-options-abrangencias', 'Cms\Controllers\FilterOptionAbrangenciaController@listar');
        Route::post('/cms/inserir-filter-option-abrangencia', 'Cms\Controllers\FilterOptionAbrangenciaController@inserir');
        Route::get('/cms/filter-option-abrangencia/{id}', 'Cms\Controllers\FilterOptionAbrangenciaController@detalhar');
        Route::post('/cms/alterar-filter-option-abrangencia/{id}', 'Cms\Controllers\FilterOptionAbrangenciaController@alterar');
        Route::get('/cms/excluir-filter-option-abrangencia/{id}', 'Cms\Controllers\FilterOptionAbrangenciaController@excluir');

        //IDIOMAS PERIODICIDADES
        Route::get('/cms/idiomas-periodicidades/{periodicidade_id}', 'Cms\Controllers\IdiomaPeriodicidadeController@index');
        Route::get('/cms/listar-idiomas-periodicidades', 'Cms\Controllers\IdiomaPeriodicidadeController@listar');
        Route::post('/cms/inserir-idioma-periodicidade', 'Cms\Controllers\IdiomaPeriodicidadeController@inserir');
        Route::get('/cms/idioma-periodicidade/{id}', 'Cms\Controllers\IdiomaPeriodicidadeController@detalhar');
        Route::post('/cms/alterar-idioma-periodicidade/{id}', 'Cms\Controllers\IdiomaPeriodicidadeController@alterar');
        Route::get('/cms/excluir-idioma-periodicidade/{id}', 'Cms\Controllers\IdiomaPeriodicidadeController@excluir');

        //IDIOMAS INDICADORES
        Route::get('/cms/idiomas-indicadores/{indicador_id}', 'Cms\Controllers\IdiomaIndicadorController@index');
        Route::get('/cms/listar-idiomas-indicadores', 'Cms\Controllers\IdiomaIndicadorController@listar');
        Route::post('/cms/inserir-idioma-indicador', 'Cms\Controllers\IdiomaIndicadorController@inserir');
        Route::get('/cms/idioma-indicador/{id}', 'Cms\Controllers\IdiomaIndicadorController@detalhar');
        Route::post('/cms/alterar-idioma-indicador/{id}', 'Cms\Controllers\IdiomaIndicadorController@alterar');
        Route::get('/cms/excluir-idioma-indicador/{id}', 'Cms\Controllers\IdiomaIndicadorController@excluir');

        //IDIOMAS CONSULTAS
        Route::get('/cms/idiomas-consultas/{consulta_id}', 'Cms\Controllers\IdiomaConsultaController@index');
        Route::get('/cms/listar-idiomas-consultas', 'Cms\Controllers\IdiomaConsultaController@listar');
        Route::post('/cms/inserir-idioma-consulta', 'Cms\Controllers\IdiomaConsultaController@inserir');
        Route::get('/cms/idioma-consulta/{id}', 'Cms\Controllers\IdiomaConsultaController@detalhar');
        Route::post('/cms/alterar-idioma-consulta/{id}', 'Cms\Controllers\IdiomaConsultaController@alterar');
        Route::get('/cms/excluir-idioma-consulta/{id}', 'Cms\Controllers\IdiomaConsultaController@excluir');

        //TEMAS SERIES
        Route::get('/cms/temas-series/{serie_id}', 'Cms\Controllers\TemaSerieController@index');
        Route::get('/cms/listar-temas-series', 'Cms\Controllers\TemaSerieController@listar');
        Route::post('/cms/inserir-tema-serie', 'Cms\Controllers\TemaSerieController@inserir');
        //Route::get('/cms/tema-serie/{id}', 'Cms\Controllers\TemaSerieController@detalhar');
        //Route::post('/cms/alterar-tema-serie/{id}', 'Cms\Controllers\TemaSerieController@alterar');
        Route::get('/cms/excluir-tema-serie/{id}', 'Cms\Controllers\TemaSerieController@excluir');

        //TEMAS CONSULTAS
        Route::get('/cms/temas-consultas/{consulta_id}', 'Cms\Controllers\TemaConsultaController@index');
        Route::get('/cms/listar-temas-consultas', 'Cms\Controllers\TemaConsultaController@listar');
        Route::post('/cms/inserir-tema-consulta', 'Cms\Controllers\TemaConsultaController@inserir');
        //Route::get('/cms/tema-consulta/{id}', 'Cms\Controllers\TemaConsultaController@detalhar');
        //Route::post('/cms/alterar-tema-consulta/{id}', 'Cms\Controllers\TemaConsultaController@alterar');
        Route::get('/cms/excluir-tema-consulta/{id}', 'Cms\Controllers\TemaConsultaController@excluir');

        //Route::get('/cms/teste-excel', 'Cms\Controllers\SerieController@testeExcel');
        //Route::get('/cms/teste-excel', 'Cms\Controllers\SerieController@testeExcel');
        Route::get('/cms/teste-excel/{id}/{arquivo}', 'Cms\Controllers\SerieController@testeExcel');

        //FILTROS SERIES
        Route::get('/cms/filtros-series/{serie_id}', 'Cms\Controllers\FiltroSerieController@index');
        Route::get('/cms/listar-filtros-series', 'Cms\Controllers\FiltroSerieController@listar');
        Route::post('/cms/inserir-filtro-serie', 'Cms\Controllers\FiltroSerieController@inserir');
        //Route::get('/cms/filtro-serie/{id}', 'Cms\Controllers\FiltroSerieController@detalhar');
        //Route::post('/cms/alterar-filtro-serie/{id}', 'Cms\Controllers\FiltroSerieController@alterar');
        Route::get('/cms/excluir-filtro-serie/{id}', 'Cms\Controllers\FiltroSerieController@excluir');

        //Route::get('/cms/teste-excel', 'Cms\Controllers\SerieController@testeExcel');
        //Route::get('/cms/teste-excel', 'Cms\Controllers\SerieController@testeExcel');
        Route::get('/cms/teste-excel/{id}/{arquivo}', 'Cms\Controllers\SerieController@testeExcel');

        //VIDEOS
        Route::get('/cms/videos', 'Cms\Controllers\VideoController@index');
        Route::get('/cms/listar-videos', 'Cms\Controllers\VideoController@listar');
        Route::post('/cms/inserir-video', 'Cms\Controllers\VideoController@inserir');
        Route::get('/cms/video/{id}', 'Cms\Controllers\VideoController@detalhar');
        Route::post('/cms/alterar-video/{id}', 'Cms\Controllers\VideoController@alterar');
        Route::get('/cms/excluir-video/{id}', 'Cms\Controllers\VideoController@excluir');
        Route::post('/cms/destaque-video-home', 'Cms\Controllers\VideoController@destaqueHome');

        //LINKS
        Route::get('/cms/links', 'Cms\Controllers\LinkController@index');
        Route::get('/cms/listar-links', 'Cms\Controllers\LinkController@listar');
        Route::post('/cms/inserir-link', 'Cms\Controllers\LinkController@inserir');
        Route::get('/cms/link/{id}', 'Cms\Controllers\LinkController@detalhar');
        Route::post('/cms/alterar-link/{id}', 'Cms\Controllers\LinkController@alterar');
        Route::get('/cms/excluir-link/{id}', 'Cms\Controllers\LinkController@excluir');

        //AUTHORS
        Route::get('/cms/authors', 'Cms\Controllers\AuthorController@index');
        Route::get('/cms/listar-authors', 'Cms\Controllers\AuthorController@listar');
        Route::post('/cms/inserir-author', 'Cms\Controllers\AuthorController@inserir');
        Route::get('/cms/author/{id}', 'Cms\Controllers\AuthorController@detalhar');
        Route::post('/cms/alterar-author/{id}', 'Cms\Controllers\AuthorController@alterar');
        Route::get('/cms/excluir-author/{id}', 'Cms\Controllers\AuthorController@excluir');
        
        //INDICES
        Route::get('/cms/indices', 'Cms\Controllers\IndiceController@index');
        Route::get('/cms/listar-indices', 'Cms\Controllers\IndiceController@listar');
        Route::post('/cms/inserir-indice', 'Cms\Controllers\IndiceController@inserir');
        Route::get('/cms/indice/{id}', 'Cms\Controllers\IndiceController@detalhar');
        Route::post('/cms/alterar-indice/{id}', 'Cms\Controllers\IndiceController@alterar');
        Route::get('/cms/excluir-indice/{id}', 'Cms\Controllers\IndiceController@excluir');
 
        //ARTWORKS
        Route::get('/cms/artworks', 'Cms\Controllers\ArtworkController@index');
        Route::get('/cms/listar-artworks', 'Cms\Controllers\ArtworkController@listar');
        Route::post('/cms/inserir-artwork', 'Cms\Controllers\ArtworkController@inserir');
        Route::get('/cms/artwork/{id}', 'Cms\Controllers\ArtworkController@detalhar');
        Route::post('/cms/alterar-artwork/{id}', 'Cms\Controllers\ArtworkController@alterar');
        Route::get('/cms/excluir-artwork/{id}', 'Cms\Controllers\ArtworkController@excluir');

        //DIRECTIVES
        Route::get('/cms/directives', 'Cms\Controllers\DirectiveController@index');
        Route::get('/cms/listar-directives', 'Cms\Controllers\DirectiveController@listar');
        Route::post('/cms/inserir-directive', 'Cms\Controllers\DirectiveController@inserir');
        Route::get('/cms/directive/{id}', 'Cms\Controllers\DirectiveController@detalhar');
        Route::post('/cms/alterar-directive/{id}', 'Cms\Controllers\DirectiveController@alterar');
        Route::get('/cms/excluir-directive/{id}', 'Cms\Controllers\DirectiveController@excluir');

        //PRINTINGS
        Route::get('/cms/printings', 'Cms\Controllers\PrintingController@index');
        Route::get('/cms/listar-printings', 'Cms\Controllers\PrintingController@listar');
        Route::post('/cms/inserir-printing', 'Cms\Controllers\PrintingController@inserir');
        Route::get('/cms/printing/{id}', 'Cms\Controllers\PrintingController@detalhar');
        Route::post('/cms/alterar-printing/{id}', 'Cms\Controllers\PrintingController@alterar');
        Route::get('/cms/excluir-printing/{id}', 'Cms\Controllers\PrintingController@excluir');

        //IDIOMAS
        Route::get('/cms/idiomas', 'Cms\Controllers\IdiomaController@index');
        Route::get('/cms/listar-idiomas', 'Cms\Controllers\IdiomaController@listar');
        Route::post('/cms/inserir-idioma', 'Cms\Controllers\IdiomaController@inserir');
        Route::get('/cms/idioma/{id}', 'Cms\Controllers\IdiomaController@detalhar');
        Route::post('/cms/alterar-idioma/{id}', 'Cms\Controllers\IdiomaController@alterar');
        Route::get('/cms/excluir-idioma/{id}', 'Cms\Controllers\IdiomaController@excluir');
        Route::get('/cms/status-idioma/{id}', 'Cms\Controllers\IdiomaController@status');


        //UNIDADES
        Route::get('/cms/unidades', 'Cms\Controllers\UnidadeController@index');
        Route::get('/cms/listar-unidades', 'Cms\Controllers\UnidadeController@listar');
        Route::post('/cms/inserir-unidade', 'Cms\Controllers\UnidadeController@inserir');
        Route::get('/cms/unidade/{id}', 'Cms\Controllers\UnidadeController@detalhar');
        Route::post('/cms/alterar-unidade/{id}', 'Cms\Controllers\UnidadeController@alterar');
        Route::get('/cms/excluir-unidade/{id}', 'Cms\Controllers\UnidadeController@excluir');

        //APIS
        Route::get('/cms/apis', 'Cms\Controllers\ApiController@index');
        Route::get('/cms/listar-apis', 'Cms\Controllers\ApiController@listar');
        Route::post('/cms/inserir-api', 'Cms\Controllers\ApiController@inserir');
        Route::get('/cms/api/{id}', 'Cms\Controllers\ApiController@detalhar');
        Route::post('/cms/alterar-api/{id}', 'Cms\Controllers\ApiController@alterar');
        Route::get('/cms/excluir-api/{id}', 'Cms\Controllers\ApiController@excluir');

        //OPTIONS ABRANGENCIAS
        Route::get('/cms/options-abrangencias', 'Cms\Controllers\OptionAbrangenciaController@index');
        Route::get('/cms/listar-options-abrangencias', 'Cms\Controllers\OptionAbrangenciaController@listar');
        Route::post('/cms/inserir-option-abrangencia', 'Cms\Controllers\OptionAbrangenciaController@inserir');
        Route::get('/cms/option-abrangencia/{id}', 'Cms\Controllers\OptionAbrangenciaController@detalhar');
        Route::post('/cms/alterar-option-abrangencia/{id}', 'Cms\Controllers\OptionAbrangenciaController@alterar');
        Route::get('/cms/excluir-option-abrangencia/{id}', 'Cms\Controllers\OptionAbrangenciaController@excluir');

        //PERIODICIDADES
        Route::get('/cms/periodicidades', 'Cms\Controllers\PeriodicidadeController@index');
        Route::get('/cms/listar-periodicidades', 'Cms\Controllers\PeriodicidadeController@listar');
        Route::post('/cms/inserir-periodicidade', 'Cms\Controllers\PeriodicidadeController@inserir');
        Route::get('/cms/periodicidade/{id}', 'Cms\Controllers\PeriodicidadeController@detalhar');
        Route::post('/cms/alterar-periodicidade/{id}', 'Cms\Controllers\PeriodicidadeController@alterar');
        Route::get('/cms/excluir-periodicidade/{id}', 'Cms\Controllers\PeriodicidadeController@excluir');

        //INDICADORES
        Route::get('/cms/indicadores', 'Cms\Controllers\IndicadorController@index');
        Route::get('/cms/listar-indicadores', 'Cms\Controllers\IndicadorController@listar');
        Route::post('/cms/inserir-indicador', 'Cms\Controllers\IndicadorController@inserir');
        Route::get('/cms/indicador/{id}', 'Cms\Controllers\IndicadorController@detalhar');
        Route::post('/cms/alterar-indicador/{id}', 'Cms\Controllers\IndicadorController@alterar');
        Route::get('/cms/excluir-indicador/{id}', 'Cms\Controllers\IndicadorController@excluir');

        //FONTES
        Route::get('/cms/fontes', 'Cms\Controllers\FonteController@index');
        Route::get('/cms/listar-fontes', 'Cms\Controllers\FonteController@listar');
        Route::post('/cms/inserir-fonte', 'Cms\Controllers\FonteController@inserir');
        Route::get('/cms/fonte/{id}', 'Cms\Controllers\FonteController@detalhar');
        Route::post('/cms/alterar-fonte/{id}', 'Cms\Controllers\FonteController@alterar');
        Route::get('/cms/excluir-fonte/{id}', 'Cms\Controllers\FonteController@excluir');

        //PADRAO TERRITORIOS
        Route::get('/cms/padrao-territorios', 'Cms\Controllers\PadraoTerritorioController@index');
        Route::get('/cms/listar-padrao-territorios', 'Cms\Controllers\PadraoTerritorioController@listar');
        Route::post('/cms/inserir-padrao-territorio', 'Cms\Controllers\PadraoTerritorioController@inserir');
        Route::get('/cms/padrao-territorio/{id}', 'Cms\Controllers\PadraoTerritorioController@detalhar');
        Route::post('/cms/alterar-padrao-territorio/{id}', 'Cms\Controllers\PadraoTerritorioController@alterar');
        Route::get('/cms/excluir-padrao-territorio/{id}', 'Cms\Controllers\PadraoTerritorioController@excluir');

        //FILTROS
        Route::get('/cms/filtros', 'Cms\Controllers\FiltroController@index');
        Route::get('/cms/listar-filtros', 'Cms\Controllers\FiltroController@listar');
        Route::post('/cms/inserir-filtro', 'Cms\Controllers\FiltroController@inserir');
        Route::get('/cms/filtro/{id}', 'Cms\Controllers\FiltroController@detalhar');
        Route::post('/cms/alterar-filtro/{id}', 'Cms\Controllers\FiltroController@alterar');
        Route::get('/cms/excluir-filtro/{id}', 'Cms\Controllers\FiltroController@excluir');

        //TEMAS
        Route::get('/cms/temas/{tema_id}', 'Cms\Controllers\TemaController@index');
        Route::get('/cms/temas', 'Cms\Controllers\TemaController@index');
        Route::get('/cms/listar-temas', 'Cms\Controllers\TemaController@listar');
        Route::post('/cms/inserir-tema', 'Cms\Controllers\TemaController@inserir');
        Route::get('/cms/tema/{id}', 'Cms\Controllers\TemaController@detalhar');
        Route::post('/cms/alterar-tema/{id}', 'Cms\Controllers\TemaController@alterar');
        Route::get('/cms/excluir-tema/{id}', 'Cms\Controllers\TemaController@excluir');
        Route::get('/cms/status-tema/{id}', 'Cms\Controllers\TemaController@status');
        Route::get('/cms/positionUp-tema/{id}', 'Cms\Controllers\TemaController@positionUp');
        Route::get('/cms/positionDown-tema/{id}', 'Cms\Controllers\TemaController@positionDown');

        //Assuntos
        Route::get('/cms/assuntos', 'Cms\Controllers\AssuntoController@index');
        Route::get('/cms/listar-assuntos', 'Cms\Controllers\AssuntoController@listar');
        Route::post('/cms/inserir-assunto', 'Cms\Controllers\AssuntoController@inserir');
        Route::get('/cms/assunto/{id}', 'Cms\Controllers\AssuntoController@detalhar');
        Route::post('/cms/alterar-assunto/{id}', 'Cms\Controllers\AssuntoController@alterar');
        Route::get('/cms/excluir-assunto/{id}', 'Cms\Controllers\AssuntoController@excluir');
        Route::get('/cms/status-assunto/{id}', 'Cms\Controllers\AssuntoController@status');
        //Route::get('/cms/positionUp-assunto/{id}', 'Cms\Controllers\AssuntoController@positionUp');
        //Route::get('/cms/positionDown-assunto/{id}', 'Cms\Controllers\AssuntoController@positionDown');

        //IDIOMAS ASSUNTOS
        Route::get('/cms/idiomas-assuntos/{assunto_id}', 'Cms\Controllers\IdiomaAssuntoController@index');
        Route::get('/cms/listar-idiomas-assuntos', 'Cms\Controllers\IdiomaAssuntoController@listar');
        Route::post('/cms/inserir-idioma-assunto', 'Cms\Controllers\IdiomaAssuntoController@inserir');
        Route::get('/cms/idioma-assunto/{id}', 'Cms\Controllers\IdiomaAssuntoController@detalhar');
        Route::post('/cms/alterar-idioma-assunto/{id}', 'Cms\Controllers\IdiomaAssuntoController@alterar');
        Route::get('/cms/excluir-idioma-assunto/{id}', 'Cms\Controllers\IdiomaAssuntoController@excluir');


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


        //WEBINDICADORES
        Route::get('/cms/webindicadores', 'Cms\Controllers\WebindicadorController@index');
        Route::get('/cms/listar-webindicadores', 'Cms\Controllers\WebindicadorController@listar');
        Route::post('/cms/inserir-webindicador', 'Cms\Controllers\WebindicadorController@inserir');
        Route::get('/cms/webindicador/{id}', 'Cms\Controllers\WebindicadorController@detalhar');
        Route::post('/cms/alterar-webindicador/{id}', 'Cms\Controllers\WebindicadorController@alterar');
        Route::get('/cms/excluir-webindicador/{id}', 'Cms\Controllers\WebindicadorController@excluir');
        Route::get('/cms/positionUp-webindicador/{id}', 'Cms\Controllers\WebindicadorController@positionUp');
        Route::get('/cms/positionDown-webindicador/{id}', 'Cms\Controllers\WebindicadorController@positionDown');
        Route::get('/cms/status-webindicador/{id}', 'Cms\Controllers\WebindicadorController@status');
        
        //CONSULTAS
        Route::get('/cms/consultas', 'Cms\Controllers\ConsultaController@index');
        Route::get('/cms/listar-consultas', 'Cms\Controllers\ConsultaController@listar');
        Route::post('/cms/inserir-consulta', 'Cms\Controllers\ConsultaController@inserir');
        Route::get('/cms/consulta/{id}', 'Cms\Controllers\ConsultaController@detalhar');
        Route::post('/cms/alterar-consulta/{id}', 'Cms\Controllers\ConsultaController@alterar');
        Route::get('/cms/excluir-consulta/{id}', 'Cms\Controllers\ConsultaController@excluir');
        Route::get('/cms/status-consulta/{id}', 'Cms\Controllers\ConsultaController@status');
        Route::get('/cms/positionUp-consulta/{id}', 'Cms\Controllers\ConsultaController@positionUp');
        Route::get('/cms/positionDown-consulta/{id}', 'Cms\Controllers\ConsultaController@positionDown');
        

        //APOIOS
        Route::get('/cms/apoios', 'Cms\Controllers\ApoioController@index');
        Route::get('/cms/listar-apoios', 'Cms\Controllers\ApoioController@listar');
        Route::post('/cms/inserir-apoio', 'Cms\Controllers\ApoioController@inserir');
        Route::get('/cms/apoio/{id}', 'Cms\Controllers\ApoioController@detalhar');
        Route::post('/cms/alterar-apoio/{id}', 'Cms\Controllers\ApoioController@alterar');
        Route::get('/cms/excluir-apoio/{id}', 'Cms\Controllers\ApoioController@excluir');
        Route::get('/cms/status-apoio/{id}', 'Cms\Controllers\ApoioController@status');
        Route::get('/cms/positionUp-apoio/{id}', 'Cms\Controllers\ApoioController@positionUp');
        Route::get('/cms/positionDown-apoio/{id}', 'Cms\Controllers\ApoioController@positionDown');
 

        //PARCEIROS
        Route::get('/cms/parceiros', 'Cms\Controllers\ParceiroController@index');
        Route::get('/cms/listar-parceiros', 'Cms\Controllers\ParceiroController@listar');
        Route::post('/cms/inserir-parceiro', 'Cms\Controllers\ParceiroController@inserir');
        Route::get('/cms/parceiro/{id}', 'Cms\Controllers\ParceiroController@detalhar');
        Route::post('/cms/alterar-parceiro/{id}', 'Cms\Controllers\ParceiroController@alterar');
        Route::get('/cms/excluir-parceiro/{id}', 'Cms\Controllers\ParceiroController@excluir');
        Route::get('/cms/status-parceiro/{id}', 'Cms\Controllers\ParceiroController@status');
        Route::get('/cms/positionUp-parceiro/{id}', 'Cms\Controllers\ParceiroController@positionUp');
        Route::get('/cms/positionDown-parceiro/{id}', 'Cms\Controllers\ParceiroController@positionDown');

        //INTEGRANTES
        Route::get('/cms/integrantes', 'Cms\Controllers\IntegranteController@index');
        Route::get('/cms/listar-integrantes', 'Cms\Controllers\IntegranteController@listar');
        Route::post('/cms/inserir-integrante', 'Cms\Controllers\IntegranteController@inserir');
        Route::get('/cms/integrante/{id}', 'Cms\Controllers\IntegranteController@detalhar');
        Route::post('/cms/alterar-integrante/{id}', 'Cms\Controllers\IntegranteController@alterar');
        Route::get('/cms/excluir-integrante/{id}', 'Cms\Controllers\IntegranteController@excluir');

        //VERSOES
        Route::get('/cms/versoes', 'Cms\Controllers\VersaoController@index');
        Route::get('/cms/listar-versoes', 'Cms\Controllers\VersaoController@listar');
        Route::post('/cms/inserir-versao', 'Cms\Controllers\VersaoController@inserir');
        Route::get('/cms/versao/{id}', 'Cms\Controllers\VersaoController@detalhar');
        Route::post('/cms/alterar-versao/{id}', 'Cms\Controllers\VersaoController@alterar');
        Route::get('/cms/excluir-versao/{id}', 'Cms\Controllers\VersaoController@excluir');
        Route::get('/cms/status-versao/{id}', 'Cms\Controllers\VersaoController@status');
        Route::get('/cms/positionUp-versao/{id}', 'Cms\Controllers\VersaoController@positionUp');
        Route::get('/cms/positionDown-versao/{id}', 'Cms\Controllers\VersaoController@positionDown');

        //ITEMS VERSAO
        Route::get('/cms/items-versao/{versao_id}', 'Cms\Controllers\ItemVersaoController@index');
        Route::get('/cms/listar-items-versao', 'Cms\Controllers\ItemVersaoController@listar');
        Route::post('/cms/inserir-item-versao', 'Cms\Controllers\ItemVersaoController@inserir');
        Route::get('/cms/item-versao/{id}', 'Cms\Controllers\ItemVersaoController@detalhar');
        Route::post('/cms/alterar-item-versao/{id}', 'Cms\Controllers\ItemVersaoController@alterar');
        Route::get('/cms/excluir-item-versao/{id}', 'Cms\Controllers\ItemVersaoController@excluir');
        Route::get('/cms/status-item-versao/{id}', 'Cms\Controllers\ItemVersaoController@status');

        //VALORES FILTRO
        Route::get('/cms/valores-filtro/{filtro_id}', 'Cms\Controllers\ValorFiltroController@index');
        Route::get('/cms/listar-valores-filtro', 'Cms\Controllers\ValorFiltroController@listar');
        Route::post('/cms/inserir-valor-filtro', 'Cms\Controllers\ValorFiltroController@inserir');
        Route::get('/cms/valor-filtro/{id}', 'Cms\Controllers\ValorFiltroController@detalhar');
        Route::post('/cms/alterar-valor-filtro/{id}', 'Cms\Controllers\ValorFiltroController@alterar');
        Route::get('/cms/excluir-valor-filtro/{id}', 'Cms\Controllers\ValorFiltroController@excluir');
        Route::get('/cms/status-valor-filtro/{id}', 'Cms\Controllers\ValorFiltroController@status');

        //FAVICONS
        Route::get('/cms/favicons', 'Cms\Controllers\FaviconController@index');
        Route::get('/cms/listar-favicons', 'Cms\Controllers\FaviconController@listar');
        Route::post('/cms/inserir-favicon', 'Cms\Controllers\FaviconController@inserir');
        Route::get('/cms/favicon/{id}', 'Cms\Controllers\FaviconController@detalhar');
        Route::post('/cms/alterar-favicon/{id}', 'Cms\Controllers\FaviconController@alterar');
        Route::get('/cms/excluir-favicon/{id}', 'Cms\Controllers\FaviconController@excluir');

        //Logs
        Route::get('/cms/logs', 'Cms\Controllers\LogController@index');
        Route::get('/cms/download-log/{log}', 'Cms\Controllers\LogController@download');


        Route::get('/cms/id-seq', 'Cms\Controllers\LogController@id_seq');

        Route::get('/cms/script', 'Cms\Controllers\ScriptController@index');
        Route::post('/cms/run-script', 'Cms\Controllers\ScriptController@run');


        //TRANSPORTES
        Route::get('/cms/transportes', 'Cms\Controllers\TransporteController@index');
        Route::get('/cms/listar-transportes', 'Cms\Controllers\TransporteController@listar');
        Route::post('/cms/inserir-transporte', 'Cms\Controllers\TransporteController@inserir');
        Route::get('/cms/transporte/{id}', 'Cms\Controllers\TransporteController@detalhar');
        Route::post('/cms/alterar-transporte/{id}', 'Cms\Controllers\TransporteController@alterar');
        Route::get('/cms/excluir-transporte/{id}', 'Cms\Controllers\TransporteController@excluir');

        //RADARES
        Route::get('/cms/radares', 'Cms\Controllers\RadarController@index');
        Route::get('/cms/listar-radares', 'Cms\Controllers\RadarController@listar');
        Route::post('/cms/inserir-radar', 'Cms\Controllers\RadarController@inserir');
        Route::get('/cms/radar/{id}', 'Cms\Controllers\RadarController@detalhar');
        Route::post('/cms/alterar-radar/{id}', 'Cms\Controllers\RadarController@alterar');
        Route::get('/cms/excluir-radar/{id}', 'Cms\Controllers\RadarController@excluir');

        //LINHAS
        Route::get('/cms/linhas/{transporte_id}', 'Cms\Controllers\LinhaController@index');
        Route::get('/cms/linhas', 'Cms\Controllers\LinhaController@index');
        Route::get('/cms/listar-linhas', 'Cms\Controllers\LinhaController@listar');
        Route::post('/cms/inserir-linha', 'Cms\Controllers\LinhaController@inserir');
        Route::get('/cms/linha/{id}', 'Cms\Controllers\LinhaController@detalhar');
        Route::post('/cms/alterar-linha/{id}', 'Cms\Controllers\LinhaController@alterar');
        Route::get('/cms/excluir-linha/{id}', 'Cms\Controllers\LinhaController@excluir');

        //IMPORTACAO
        Route::post('/cms/importar', 'Cms\Controllers\ImportarController@importar');
        Route::get('/cms/importar-series', 'Cms\Controllers\ImportarController@viewImportarVarias');

        //GERAR CSV///////////////////////////////////////////////////////////////
        Route::get('/cms/csv-metro', 'Cms\Controllers\ImportarController@csvMetro');
        Route::get('/cms/csv-brt', 'Cms\Controllers\ImportarController@csvBrt');
        Route::get('/cms/csv-vlt', 'Cms\Controllers\ImportarController@csvVlt');
        Route::get('/cms/csv-trem', 'Cms\Controllers\ImportarController@csvTrem');
        Route::get('/cms/csv-barca', 'Cms\Controllers\ImportarController@csvBarca');
        Route::get('/cms/csv-aeroporto', 'Cms\Controllers\ImportarController@csvAeroporto');
        Route::get('/cms/csv-bonde', 'Cms\Controllers\ImportarController@csvBonde');
        Route::get('/cms/csv-teleferico', 'Cms\Controllers\ImportarController@csvTeleferico');
        Route::get('/cms/csv-bicicletario', 'Cms\Controllers\ImportarController@csvBicicletario');
        //////////////////////////////////////////////////////////////////////////

    });

});
