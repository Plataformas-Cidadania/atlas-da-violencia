cmsApp.controller('alterarPresentationCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){

    $scope.processandoSalvar = false;



    //ALTERAR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;

    $scope.mostrarForm = false;

    $scope.removerImagem = 0;
    $scope.removerArquivo = 0;

    $scope.alterar = function (file, arquivo){

        $scope.processandoSalvar = true;
        $scope.mensagemSalvar = "";

        if(file==null && arquivo==null){

            //console.log($scope.presentation);
            $http.post("cms/alterar-presentation/"+$scope.id, {
                'presentation': $scope.presentation,
                'removerImagem': $scope.removerImagem,
                'removerArquivo': $scope.removerArquivo,
                'author_presentation': $scope.author_presentation
            }).success(function (data){
                //console.log(data);
                $scope.processandoSalvar = false;
                $scope.mensagemSalvar = "Gravado com Sucesso";
                $scope.removerImagem = false;
            }).error(function(data){
                //console.log(data);
                $scope.mensagemSalvar = "Ocorreu um erro: "+data;
                $scope.processandoSalvar = false;
            });

        }else{

            var data1 = {
                presentation: $scope.presentation,
                'removerImagem': $scope.removerImagem,
                'removerArquivo': $scope.removerArquivo,
                'author_presentation': $scope.author_presentation
            };

            if(file!=null){
                data1.file = file;
            }
            if(arquivo!=null){
                data1.arquivo = arquivo;
            }

            Upload.upload({
                url: 'cms/alterar-presentation/'+$scope.id,
                data: data1
            }).then(function (response) {
                $timeout(function () {
                    $scope.result = response.data;
                });
                $scope.picFile = null;//limpa o file
                //$scope.fileArquivo = null;//limpa o file
                $scope.mensagemSalvar =  "Gravado com sucesso!";
                $scope.removerImagem = false;
                $scope.imagemBD = '/imagens/presentations/'+response.data;
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

        }

    };

    $scope.limparImagem = function(){
        $scope.picFile = null;
        $scope.imagemBD = null;
        $scope.removerImagem = 1;
    };

    $scope.limparArquivo= function(){
        $scope.fileArquivo = null;
        $scope.arquivoBD = null;
        $scope.removerArquivo = 1;
    };

    $scope.carregaImagem  = function(img, arquivo) {
        if(img!=''){
            $scope.imagemBD = 'imagens/presentations/xs-'+img;
            //console.log($scope.imagemBD);
        }
        if(arquivo!=''){
            $scope.arquivoBD = arquivo;
            //console.log($scope.baseBD);
        }
    };

    $scope.validar = function(valor) {
        if(valor===undefined && $scope.form.$dirty){
            return "campo-obrigatorio";
        }
        return "";
    };
    /////////////////////////////////
    
    


}]);
