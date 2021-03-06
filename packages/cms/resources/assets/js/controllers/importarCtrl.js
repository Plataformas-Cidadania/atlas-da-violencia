cmsApp.controller('importarCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){

    $scope.processandoSalvar = false;
    $scope.tinymceOptions = tinymceOptions;
    $scope.mostrarForm = false;
    $scope.removerArquivo = 0;
    $scope.modelo = '1';

    $scope.importar = function (arquivo){

        $scope.errorMsg = '';
        $scope.mensagemSalvar = '';


        $scope.processandoSalvar = true;
        var data1 = {
            tipo_dados: $scope.tipo_dados,
        };

        if(arquivo!=null){
            data1.arquivo = arquivo;
        }

        console.log(data1);

        Upload.upload({
            url: 'cms/importar',
            data: data1,
            cache:false
        }).then(function (response) {



            $timeout(function () {
                $scope.result = response.data;
            });

            if(response.data.erro){
                $scope.mensagemSalvar = response.data.msg;
                $scope.processandoSalvar = false;
                return;
            }

            //$scope.fileArquivo = null;//limpa o file
            console.log($scope.result);
            $scope.mensagemSalvar =  "Gravado com sucesso!";
            //$scope.removerImagem = false;
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

    $scope.limparArquivo = function(){
        delete $scope.fileArquivo;
        $scope.form.fileArquivo.$error.maxSize = false;
    };

    $scope.validar = function(valor) {
        if(valor===undefined && $scope.form.$dirty){
            return "campo-obrigatorio";
        }
        return "";
    };
    /////////////////////////////////

}]);
