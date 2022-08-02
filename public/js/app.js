//var ipeaApp = angular.module('ipeaApp', ['ui.bootstrap'] ,['$interpolateProvider', function($interpolateProvider) {
var ipeaApp = angular.module('ipeaApp', [] ,['$interpolateProvider', function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
}]);

//window.sessionStorage.setItem('alto-contraste', true);
var altoContraste = window.sessionStorage.getItem('alto-contraste');

ipeaApp.factory('altoContraste', function() {
    return altoContraste;
});

ipeaApp.controller('appCtrl', ['$scope', 'altoContraste', function($scope, altoContraste){
    $scope.altoContrasteAtivo = altoContraste;


    $scope.setAltoContraste = function(){
        $scope.altoContrasteAtivo = !$scope.altoContrasteAtivo;
        //console.log("scopo: "+$scope.altoContrasteAtivo);

        window.sessionStorage.removeItem('alto-contraste');
        if($scope.altoContrasteAtivo){
            window.sessionStorage.setItem('alto-contraste', $scope.altoContrasteAtivo);
        }

        //console.log("sessao: "+window.sessionStorage.getItem('alto-contraste'));
    }

}]);
