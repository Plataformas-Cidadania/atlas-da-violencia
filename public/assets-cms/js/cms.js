var cmsApp = angular.module('cmsApp', ['ui.bootstrap', 'ui.tinymce', 'ngFileUpload'] ,['$interpolateProvider', function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
}]);

cmsApp.filter('removeHTMLTags', function() {
    return function(text) {
        return  text ? String(text).replace(/<[^>]+>/gm, '') : '';
    };
});