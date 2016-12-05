

ipeaApp.controller('contatoCtrl', ['$scope', '$http', function($scope, $http){
     //INSERIR/////////////////////////////
    $scope.mostrarForm = false;
    $scope.enviandoContato = false;
    $scope.erroContato = false;
    $scope.enviadoContato = false;

    $scope.inserir = function (){
        $scope.erroContato = false;
        $scope.enviadoContato = false;
        $scope.enviandoContato = true;
        //console.log($scope.contato);
        $http.post("enviar-contato", $scope.contato).success(function (data){
            $scope.enviandoContato = false;
            $scope.enviadoContato = true;
            console.log(data);
            delete $scope.contato;//limpa o form
            $scope.frmContato.email.$dirty = false;
        }).error(function(data){
            $scope.erroContato = true;
            $scope.enviandoContato = false;
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

