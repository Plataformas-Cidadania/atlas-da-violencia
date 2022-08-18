process.env.DISABLE_NOTIFIER = true;
var elixir = require('laravel-elixir');
elixir.config.sourcemaps = false;

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

/*elixir(function(mix) {
    mix.sass('app.scss');
});*/

elixir(function(mix) {
    //CMS///////////////////////////////////////////////////////////////////
    //css
    mix.less('../../../packages/cms/resources/assets/less/cms.less', 'public/assets-cms/css/cms.css');
    mix.styles('../../../packages/cms/resources/assets/css/sb-admin.css', 'public/assets-cms/css/sb-admin.css');
    mix.styles('../../../packages/cms/resources/assets/css/circle.css', 'public/assets-cms/css/circle.css');

    //App angular
    mix.scripts('../../../packages/cms/resources/assets/js/cms.js', 'public/assets-cms/js/cms.js');

    mix.scripts('../../../packages/cms/resources/assets/js/tiny.js', 'public/assets-cms/js/tiny.js');

    //Directives
    mix.scripts('../../../packages/cms/resources/assets/js/directives/initModel.js', 'public/assets-cms/js/directives/initModel.js');

    //Controllers

    //Quem Somos
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/quemsomoCtrl.js', 'public/assets-cms/js/controllers/quemsomoCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarQuemsomoCtrl.js', 'public/assets-cms/js/controllers/alterarQuemsomoCtrl.js');

    //Mensagens
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/mensagemCtrl.js', 'public/assets-cms/js/controllers/mensagemCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarMensagemCtrl.js', 'public/assets-cms/js/controllers/alterarMensagemCtrl.js');

    //Webdoor
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/webdoorCtrl.js', 'public/assets-cms/js/controllers/webdoorCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarWebdoorCtrl.js', 'public/assets-cms/js/controllers/alterarWebdoorCtrl.js');

    //Destaque
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/destaqueCtrl.js', 'public/assets-cms/js/controllers/destaqueCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarDestaqueCtrl.js', 'public/assets-cms/js/controllers/alterarDestaqueCtrl.js');


    //Noticias
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/noticiaCtrl.js', 'public/assets-cms/js/controllers/noticiaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarNoticiaCtrl.js', 'public/assets-cms/js/controllers/alterarNoticiaCtrl.js');

    //Menus
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/menuCtrl.js', 'public/assets-cms/js/controllers/menuCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarMenuCtrl.js', 'public/assets-cms/js/controllers/alterarMenuCtrl.js');

    //Artigos
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/artigoCtrl.js', 'public/assets-cms/js/controllers/artigoCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarArtigoCtrl.js', 'public/assets-cms/js/controllers/alterarArtigoCtrl.js');

    //Presentations
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/presentationCtrl.js', 'public/assets-cms/js/controllers/presentationCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarPresentationCtrl.js', 'public/assets-cms/js/controllers/alterarPresentationCtrl.js');

    //Presentation Elements
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/presentationElementCtrl.js', 'public/assets-cms/js/controllers/presentationElementCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarPresentationElementCtrl.js', 'public/assets-cms/js/controllers/alterarPresentationElementCtrl.js');

    //Series
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/serieCtrl.js', 'public/assets-cms/js/controllers/serieCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarSerieCtrl.js', 'public/assets-cms/js/controllers/alterarSerieCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/importSerieCtrl.js', 'public/assets-cms/js/controllers/importSerieCtrl.js');

    //Textos Series
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/textoSerieCtrl.js', 'public/assets-cms/js/controllers/textoSerieCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarTextoSerieCtrl.js', 'public/assets-cms/js/controllers/alterarTextoSerieCtrl.js');

    //Idiomas Temas
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaTemaCtrl.js', 'public/assets-cms/js/controllers/idiomaTemaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaTemaCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaTemaCtrl.js');

    //Idiomas Assuntos
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaAssuntoCtrl.js', 'public/assets-cms/js/controllers/idiomaAssuntoCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaAssuntoCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaAssuntoCtrl.js');

    //Idiomas Transportes
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaTransporteCtrl.js', 'public/assets-cms/js/controllers/idiomaTransporteCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaTransporteCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaTransporteCtrl.js');

    //Idiomas Radares
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaRadarCtrl.js', 'public/assets-cms/js/controllers/idiomaRadarCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaRadarCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaRadarCtrl.js');

    //Idiomas Linhas
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaLinhaCtrl.js', 'public/assets-cms/js/controllers/idiomaLinhaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaLinhaCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaLinhaCtrl.js');

    //Idiomas Unidades
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaUnidadeCtrl.js', 'public/assets-cms/js/controllers/idiomaUnidadeCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaUnidadeCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaUnidadeCtrl.js');

    //Idiomas Apis
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaApiCtrl.js', 'public/assets-cms/js/controllers/idiomaApiCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaApiCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaApiCtrl.js');

    //Idiomas Options Abrangencias
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaOptionAbrangenciaCtrl.js', 'public/assets-cms/js/controllers/idiomaOptionAbrangenciaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaOptionAbrangenciaCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaOptionAbrangenciaCtrl.js');

    //Filters Options Abrangencias
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/filterOptionAbrangenciaCtrl.js', 'public/assets-cms/js/controllers/filterOptionAbrangenciaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarFilterOptionAbrangenciaCtrl.js', 'public/assets-cms/js/controllers/alterarFilterOptionAbrangenciaCtrl.js');

    //Idiomas Periodicidades
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaPeriodicidadeCtrl.js', 'public/assets-cms/js/controllers/idiomaPeriodicidadeCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaPeriodicidadeCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaPeriodicidadeCtrl.js');

    //Idiomas Indicadores
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaIndicadorCtrl.js', 'public/assets-cms/js/controllers/idiomaIndicadorCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaIndicadorCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaIndicadorCtrl.js');

    //Idiomas Consultas
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaConsultaCtrl.js', 'public/assets-cms/js/controllers/idiomaConsultaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaConsultaCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaConsultaCtrl.js');

    //Temas Series
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/temaSerieCtrl.js', 'public/assets-cms/js/controllers/temaSerieCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarTemaSerieCtrl.js', 'public/assets-cms/js/controllers/alterarTemaSerieCtrl.js');

    //Temas Consultas
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/temaConsultaCtrl.js', 'public/assets-cms/js/controllers/temaConsultaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarTemaConsultaCtrl.js', 'public/assets-cms/js/controllers/alterarTemaConsultaCtrl.js');

    //Filtros Series
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/filtroSerieCtrl.js', 'public/assets-cms/js/controllers/filtroSerieCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarFiltroSerieCtrl.js', 'public/assets-cms/js/controllers/alterarFiltroSerieCtrl.js');


    //Videos
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/videoCtrl.js', 'public/assets-cms/js/controllers/videoCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarVideoCtrl.js', 'public/assets-cms/js/controllers/alterarVideoCtrl.js');

    //Links
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/linkCtrl.js', 'public/assets-cms/js/controllers/linkCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarLinkCtrl.js', 'public/assets-cms/js/controllers/alterarLinkCtrl.js');

    //Authors
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/authorCtrl.js', 'public/assets-cms/js/controllers/authorCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarAuthorCtrl.js', 'public/assets-cms/js/controllers/alterarAuthorCtrl.js');

    //Indices
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/indiceCtrl.js', 'public/assets-cms/js/controllers/indiceCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIndiceCtrl.js', 'public/assets-cms/js/controllers/alterarIndiceCtrl.js');

    //Artworks
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/artworkCtrl.js', 'public/assets-cms/js/controllers/artworkCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarArtworkCtrl.js', 'public/assets-cms/js/controllers/alterarArtworkCtrl.js');

    //Directives
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/directiveCtrl.js', 'public/assets-cms/js/controllers/directiveCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarDirectiveCtrl.js', 'public/assets-cms/js/controllers/alterarDirectiveCtrl.js');

    //Printings
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/printingCtrl.js', 'public/assets-cms/js/controllers/printingCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarPrintingCtrl.js', 'public/assets-cms/js/controllers/alterarPrintingCtrl.js');

    //Idiomas
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/idiomaCtrl.js', 'public/assets-cms/js/controllers/idiomaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIdiomaCtrl.js', 'public/assets-cms/js/controllers/alterarIdiomaCtrl.js');

    //Unidades
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/unidadeCtrl.js', 'public/assets-cms/js/controllers/unidadeCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarUnidadeCtrl.js', 'public/assets-cms/js/controllers/alterarUnidadeCtrl.js');

    //Apis
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/apiCtrl.js', 'public/assets-cms/js/controllers/apiCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarApiCtrl.js', 'public/assets-cms/js/controllers/alterarApiCtrl.js');

    //Options Abrangencias
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/optionAbrangenciaCtrl.js', 'public/assets-cms/js/controllers/optionAbrangenciaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarOptionAbrangenciaCtrl.js', 'public/assets-cms/js/controllers/alterarOptionAbrangenciaCtrl.js');

    //Periodicidades
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/periodicidadeCtrl.js', 'public/assets-cms/js/controllers/periodicidadeCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarPeriodicidadeCtrl.js', 'public/assets-cms/js/controllers/alterarPeriodicidadeCtrl.js');

    //Indicadores
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/indicadorCtrl.js', 'public/assets-cms/js/controllers/indicadorCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIndicadorCtrl.js', 'public/assets-cms/js/controllers/alterarIndicadorCtrl.js');


    //PadraoTerritorios
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/padraoTerritorioCtrl.js', 'public/assets-cms/js/controllers/padraoTerritorioCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarPadraoTerritorioCtrl.js', 'public/assets-cms/js/controllers/alterarPadraoTerritorioCtrl.js');

    //Filtros
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/filtroCtrl.js', 'public/assets-cms/js/controllers/filtroCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarFiltroCtrl.js', 'public/assets-cms/js/controllers/alterarFiltroCtrl.js');

    //Temas
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/temaCtrl.js', 'public/assets-cms/js/controllers/temaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarTemaCtrl.js', 'public/assets-cms/js/controllers/alterarTemaCtrl.js');

    //Assuntos
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/assuntoCtrl.js', 'public/assets-cms/js/controllers/assuntoCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarAssuntoCtrl.js', 'public/assets-cms/js/controllers/alterarAssuntoCtrl.js');

    //Downloads
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/downloadCtrl.js', 'public/assets-cms/js/controllers/downloadCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarDownloadCtrl.js', 'public/assets-cms/js/controllers/alterarDownloadCtrl.js');

    //CmsUsers
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/cmsUserCtrl.js', 'public/assets-cms/js/controllers/cmsUserCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarCmsUserCtrl.js', 'public/assets-cms/js/controllers/alterarCmsUserCtrl.js');

    //Webindicador
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/webindicadorCtrl.js', 'public/assets-cms/js/controllers/webindicadorCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarWebindicadorCtrl.js', 'public/assets-cms/js/controllers/alterarWebindicadorCtrl.js');

    //Consultas
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/consultaCtrl.js', 'public/assets-cms/js/controllers/consultaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarConsultaCtrl.js', 'public/assets-cms/js/controllers/alterarConsultaCtrl.js');


    //Apoios
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/apoioCtrl.js', 'public/assets-cms/js/controllers/apoioCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarApoioCtrl.js', 'public/assets-cms/js/controllers/alterarApoioCtrl.js');

    //Parceiros
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/parceiroCtrl.js', 'public/assets-cms/js/controllers/parceiroCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarParceiroCtrl.js', 'public/assets-cms/js/controllers/alterarParceiroCtrl.js');

    //Favicons
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/faviconCtrl.js', 'public/assets-cms/js/controllers/faviconCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarFaviconCtrl.js', 'public/assets-cms/js/controllers/alterarFaviconCtrl.js');

    //Settings
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarSettingCtrl.js', 'public/assets-cms/js/controllers/alterarSettingCtrl.js');

    //Integrantes
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/integranteCtrl.js', 'public/assets-cms/js/controllers/integranteCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarIntegranteCtrl.js', 'public/assets-cms/js/controllers/alterarIntegranteCtrl.js');

    //Versoes
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/versaoCtrl.js', 'public/assets-cms/js/controllers/versaoCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarVersaoCtrl.js', 'public/assets-cms/js/controllers/alterarVersaoCtrl.js');

    //Items Versao
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/itemVersaoCtrl.js', 'public/assets-cms/js/controllers/itemVersaoCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarItemVersaoCtrl.js', 'public/assets-cms/js/controllers/alterarItemVersaoCtrl.js');

    //Valores Filtro
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/valorFiltroCtrl.js', 'public/assets-cms/js/controllers/valorFiltroCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarValorFiltroCtrl.js', 'public/assets-cms/js/controllers/alterarValorFiltroCtrl.js');

    //Transportes
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/transporteCtrl.js', 'public/assets-cms/js/controllers/transporteCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarTransporteCtrl.js', 'public/assets-cms/js/controllers/alterarTransporteCtrl.js');

    //Radares
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/radarCtrl.js', 'public/assets-cms/js/controllers/radarCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarRadarCtrl.js', 'public/assets-cms/js/controllers/alterarRadarCtrl.js');

    //Linhas
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/linhaCtrl.js', 'public/assets-cms/js/controllers/linhaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarLinhaCtrl.js', 'public/assets-cms/js/controllers/alterarLinhaCtrl.js');

    mix.scripts('../../../packages/cms/resources/assets/js/controllers/importarCtrl.js', 'public/assets-cms/js/controllers/importarCtrl.js');

    //Texts
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/textCtrl.js', 'public/assets-cms/js/controllers/textCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarTextCtrl.js', 'public/assets-cms/js/controllers/alterarTextCtrl.js');

    //FIM CMS///////////////////////////////////////////////////////////////////


    //SITE///////////////////////////////////////////////////////////////////
    //css
    mix.less('app.less');

    //App angular
    mix.scripts('app.js');

    //Directives
    mix.scripts('directives/maskPhoneDir.js', 'public/js/directives/maskPhoneDir.js');

    //Controllers
    //Contato
    mix.scripts('controllers/contatoCtrl.js', 'public/js/controllers/contatoCtrl.js');
    //ContatoSerie
    mix.scripts('controllers/contatoSerieCtrl.js', 'public/js/controllers/contatoSerieCtrl.js');
    //Serie
    mix.scripts('controllers/serieCtrl.js', 'public/js/controllers/serieCtrl.js');
    //Home
    mix.scripts('controllers/linkCtrl.js', 'public/js/controllers/linkCtrl.js');
    mix.scripts('chart/Chart.bundle.js', 'public/js/chart/Chart.bundle.js');
    mix.scripts('chart/utils.js', 'public/js/chart/utils.js');
    mix.scripts('chart/chartAnimate.js', 'public/js/chart/chartAnimate.js');


    mix.scripts([
        'lib/jquery/jquery.min.js',
        'lib/bootstrap/js/bootstrap.min.js',
        'lib/angular/angular.min.js',
        'lib/angular/angular-locale_pt-br.js',
        'lib/jquery/jquery.smoove.min.js',
        'app.js',
        'utils.js',
        'lib/range/ion.rangeSlider.js',
        'lib/numeral.js',
        'lib/html2canvas.js'
    ]);

    mix.styles([
        'lib/bootstrap/bootstrap.min.css',
        /*'lib/bootstrap/bootstrap-theme.min.css',*/
        'lib/font-awesome/font-awesome.min.css',
        'lib/range/ion.rangeSlider.css',
        'lib/range/ion.rangeSlider.skinFlat.css'
    ]);

    //FIM SITE///////////////////////////////////////////////////////////////////



});
