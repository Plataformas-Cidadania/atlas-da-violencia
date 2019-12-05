cmsApp.controller('alterarSettingCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){

    $scope.processandoSalvar = false;



    //ALTERAR/////////////////////////////

   // $scope.tinymceOptions = tinymceOptions;

    $scope.mostrarForm = false;
    $scope.removerImagem = 0;
    $scope.removerCsvSerie = 0;

    $scope.alterar = function (file, csv_serie){
       // console.log($scope.setting);
        if(file==null && csv_serie==null){

            $scope.processandoSalvar = true;
            //console.log($scope.setting);
            $http.post("cms/alterar-setting/"+$scope.id,
                {
                    'setting': $scope.setting,
                    'removerImagem': $scope.removerImagem,
                    'removerCsvSerie': $scope.removerCsvSerie
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

            Upload.upload({
                url: 'cms/alterar-setting/'+$scope.id,
                data: {setting: $scope.setting, file: file, csv_serie: csv_serie, removerImagem: $scope.removerImagem, removerCsvSerie: $scope.removerCsvSerie},
            }).then(function (response) {
                $timeout(function () {
                    if(file){
                        file.result = response.data;
                    }
                });
                $scope.picFile = null;//limpa o form
                $scope.mensagemSalvar =  "Gravado com sucesso!";
                $scope.removerImagem = 0;
                $scope.removerCsvSerie = 0;
                $scope.imagemBD = 'imagens/settings/'+response.data;
                //console.log($scope.imagemDB);
            }, function (response) {
                if (response.status > 0){
                    $scope.errorMsg = response.status + ': ' + response.data;
                }
            }, function (evt) {
                //console.log(evt);
                if(file){
                    // Math.min is to fix IE which reports 200% sometimes
                    file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
                }
            });

        }
    };
    $scope.limparImagem = function(){
        $scope.picFile = null;
        $scope.imagemBD = null;
        $scope.removerImagem = true;
    };

    $scope.limparCsvSerie= function(){
        $scope.fileCsvSerie = null;
        $scope.csvSerieBD = null;
        $scope.removerCsvSerie = 1;
    };

    $scope.carregaImagem  = function(img, csv_serie) {
        if(img!=''){
            $scope.imagemBD = 'imagens/settings/xs-'+img;
            //console.log($scope.imagemBD);
        }
        if(csv_serie!=''){
            $scope.csvSerieBD = csv_serie;
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
