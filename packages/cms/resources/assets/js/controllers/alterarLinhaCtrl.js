cmsApp.controller('alterarLinhaCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){

    $scope.processandoSalvar = false;


    //ALTERAR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;

    $scope.mostrarForm = false;

    $scope.removerImagem = 0;
    $scope.removerIcone = 0;

    $scope.alterar = function (file, fileIcone){

        if(file==null && fileIcone==null){

            $scope.processandoSalvar = true;
            //console.log($scope.linha);
            $http.post("cms/alterar-linha/"+$scope.id, {'linha': $scope.linha, 'removerImagem': $scope.removerImagem, 'removerIcone': $scope.removerIcone}).success(function (data){
                //console.log(data);
                $scope.processandoSalvar = false;
                $scope.mensagemSalvar = data;
                $scope.removerImagem = false;
                $scope.removerIcone = false;
            }).error(function(data){
                //console.log(data);
                $scope.mensagemSalvar = "Ocorreu um erro: "+data;
                $scope.processandoSalvar = false;
            });

        }else{

            var data1 = {
                linha: $scope.linha,
                'removerImagem': $scope.removerImagem,
                'removerIcone': $scope.removerIcone
            };

            if(file!=null){
                data1.file = file;
            }
            if(fileIcone!=null){
                data1.fileIcone = fileIcone;
            }

            Upload.upload({
                url: 'cms/alterar-linha/'+$scope.id,
                data: data1
            }).then(function (response) {
                $timeout(function () {
                    $scope.result = response.data;
                });
                $scope.picFile = null;//limpa o file
                //$scope.fileArquivo = null;//limpa o file
                $scope.mensagemSalvar =  "Gravado com sucesso!";
                $scope.removerImagem = false;
                $scope.imagemBD = '/imagens/linha/'+response.data;
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
        $scope.removerImagem = true;
    };
    $scope.limparIcone = function(){
        $scope.picFileIcone = null;
        $scope.iconeBD = null;
        $scope.removerIcone = 1;
    };

    $scope.carregaImagem  = function(img, icone) {
        if(img!=''){
            $scope.imagemBD = 'imagens/linhas/xs-'+img;
            //console.log($scope.imagemBD);
        }
        if(icone!=''){
            $scope.iconeBD = 'imagens/linhas_icones/xs-'+icone;
            //console.log($scope.iconeBD);
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
