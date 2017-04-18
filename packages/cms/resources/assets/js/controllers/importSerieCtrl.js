cmsApp.controller('importSerieCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){

    $scope.processandoSalvar = false;
    $scope.tinymceOptions = tinymceOptions;
    $scope.mostrarForm = false;
    $scope.removerArquivo = 0;

    $scope.importar = function (arquivo){
        var data1 = {
            id: $scope.id,
            serie: $scope.serie,
        };

        if(arquivo!=null){
            data1.arquivo = arquivo;
        }


        Upload.upload({
            url: 'atlasviolencia/cms/importar-serie2/',
            data: data1
        }).then(function (response) {
            $timeout(function () {
                $scope.result = response.data;
            });
            //$scope.fileArquivo = null;//limpa o file
            console.log($scope.result);
            $scope.mensagemSalvar =  "Gravado com sucesso!";
            $scope.removerImagem = false;
            $scope.imagemBD = '/imagens/downloads/'+response.data;
            $scope.processandoSalvar = false;

        }, function (response) {
            if (response.status > 0){
                $scope.errorMsg = response.status + ': ' + response.data;
                $scope.processandoSalvar = false;
            }
        }, function (evt) {
            //console.log(evt);
            // Math.min is to fix IE which reports 200% sometimes
            $scope.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
        });

    };

    $scope.validar = function(valor) {
        if(valor===undefined && $scope.form.$dirty){
            return "campo-obrigatorio";
        }
        return "";
    };
    /////////////////////////////////



}]);
