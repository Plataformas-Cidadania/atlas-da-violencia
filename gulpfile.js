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

    //Webdoor
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/webdoorCtrl.js', 'public/assets-cms/js/controllers/webdoorCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarWebdoorCtrl.js', 'public/assets-cms/js/controllers/alterarWebdoorCtrl.js');

    //Noticias
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/noticiaCtrl.js', 'public/assets-cms/js/controllers/noticiaCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarNoticiaCtrl.js', 'public/assets-cms/js/controllers/alterarNoticiaCtrl.js');

    //Links
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/linkCtrl.js', 'public/assets-cms/js/controllers/linkCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarLinkCtrl.js', 'public/assets-cms/js/controllers/alterarLinkCtrl.js');

    //CmsUsers
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/cmsUserCtrl.js', 'public/assets-cms/js/controllers/cmsUserCtrl.js');
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarCmsUserCtrl.js', 'public/assets-cms/js/controllers/alterarCmsUserCtrl.js');
    
    //Settings
    mix.scripts('../../../packages/cms/resources/assets/js/controllers/alterarSettingCtrl.js', 'public/assets-cms/js/controllers/alterarSettingCtrl.js');

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
    //Serie
    mix.scripts('controllers/serieCtrl.js', 'public/js/controllers/serieCtrl.js');
    //Home
    mix.scripts('controllers/linkCtrl.js', 'public/js/controllers/linkCtrl.js');


    mix.scripts([
        'lib/jquery/jquery.min.js',        
        'lib/bootstrap/js/bootstrap.min.js',
        'lib/angular/angular.min.js',
        'lib/angular/angular-locale_pt-br.js',
        'lib/chart/d3.v3.min.js',
        'lib/chart/c3.js',
        'app.js',
        'lib/chart/cd3.js',
        'lib/range/ion.rangeSlider.js'
    ]);

    mix.styles([
        'lib/bootstrap/bootstrap.min.css',
        'lib/bootstrap/bootstrap-theme.min.css',
        'lib/font-awesome/font-awesome.min.css',
        'lib/chart/c3.min.css',
        'lib/range/ion.rangeSlider.css',
        'lib/range/ion.rangeSlider.skinFlat.css'
    ]);

    //FIM SITE///////////////////////////////////////////////////////////////////



});
