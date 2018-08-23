ipeaApp.controller('contatoSerieCtrl', ['$scope', '$http', function($scope, $http){
     //INSERIR/////////////////////////////
    $scope.mostrarForm = false;
    $scope.enviandoContatoSerie = false;
    $scope.erroContatoSerie = false;
    $scope.enviadoContatoSerie = false;

    $scope.inserir = function (){
        $scope.erroContatoSerie = false;
        $scope.enviadoContatoSerie = false;
        $scope.enviandoContatoSerie = true;
        //console.log($scope.contatoSerie);
        $http.post("enviar-contato-serie", $scope.contatoSerie).success(function (data){
            $scope.enviandoContatoSerie = false;
            $scope.enviadoContatoSerie = true;
            console.log(data);
            //delete $scope.contatoSerie;//limpa o form
            delete $scope.contatoSerie.nome;//limpa o form
            delete $scope.contatoSerie.email;//limpa o form
            delete $scope.contatoSerie.telefone;//limpa o form
            delete $scope.contatoSerie.mensagem;//limpa o form
            $scope.frmContatoSerie.email.$dirty = false;
        }).error(function(data){
            $scope.erroContatoSerie = true;
            $scope.enviandoContatoSerie = false;
            console.log(data);
            //$scope.messageInserir = "Ocorreu um erro: "+data;
        });
    };

    $scope.validar = function(valor) {
        //console.log(valor);
        if(valor===undefined){
            return "campo-obrigatorio";
        }
        return "";
    };
    /////////////////////////////////
}]);

