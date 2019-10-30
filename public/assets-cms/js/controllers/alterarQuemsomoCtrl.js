cmsApp.controller('alterarQuemsomoCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){

    $scope.processandoSalvar = false;

    $scope.quemsomo = null;

    //ALTERAR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;

    $scope.mostrarForm = false;

    $scope.removerImagem = false;

    $scope.$watch('quemsomo', function(){
        $scope.setTipoUrl();
    });

    $scope.alterar = function (file){

        if(file==null){

            $scope.processandoSalvar = true;
            //console.log($scope.quemsomo);
            $http.post("cms/alterar-quemsomo/"+$scope.id, {
                'quemsomo': $scope.quemsomo,
                tipoUrl: $scope.tipoUrl,
                'removerImagem': $scope.removerImagem
            }).success(function (data){
                //console.log(data);
                $scope.processandoSalvar = false;
                $scope.mensagemSalvar = data;
                $scope.removerImagem = false;
            }).error(function(data){
                //console.log(data);
                $scope.mensagemSalvar = "Ocorreu um erro: "+data;
                $scope.processandoSalvar = false;
            });

        }else{

            file.upload = Upload.upload({
                url: 'cms/alterar-quemsomo/'+$scope.id,
                data: {quemsomo: $scope.quemsomo, file: file, tipoUrl: $scope.tipoUrl},
            });

            file.upload.then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                });
                $scope.picFile = null;//limpa o form
                $scope.mensagemSalvar =  "Gravado com sucesso!";
                $scope.removerImagem = false;
                $scope.imagemBD = 'imagens/quemsomos/'+response.data;
                console.log($scope.imagemDB);
            }, function (response) {
                if (response.status > 0){
                    $scope.errorMsg = response.status + ': ' + response.data;
                }
            }, function (evt) {
                //console.log(evt);
                // Math.min is to fix IE which reports 200% sometimes
                file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
            });

        }

    };

    $scope.limparImagem = function(){
        $scope.picFile = null;
        $scope.imagemBD = null;
        $scope.removerImagem = true;
    };

    $scope.carregaImagem  = function(img) {
        if(img!=''){
            $scope.imagemBD = 'imagens/quemsomos/xs-'+img;
            //console.log($scope.imagemBD);
        }
    };

    $scope.validar = function(valor) {
        if(valor===undefined && $scope.form.$dirty){
            return "campo-obrigatorio";
        }
        return "";
    };
    /////////////////////////////////
    
    
    $scope.setTipoUrl = function(){
        console.log($scope.quemsomo);
        let arrayUrl = $scope.quemsomo['url'].split('|');
        console.log(arrayUrl);
        $scope.quemsomo.url = arrayUrl[0];
        if(arrayUrl.length === 1){
            $scope.tipoUrl = 0;
            return;
        }
        $scope.tipoUrl = arrayUrl[1];
    };
    

}]);
