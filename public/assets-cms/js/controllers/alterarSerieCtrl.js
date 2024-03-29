cmsApp.controller('alterarSerieCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){

    $scope.processandoSalvar = false;



    //ALTERAR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;

    $scope.mostrarForm = false;

    $scope.removerImagem = 0;
    $scope.removerArquivo = 0;
    $scope.removerArquivoMetadados = 0;

    $scope.alterar = function (file, arquivo, arquivoMetadados){

        if(file==null && arquivo==null && arquivoMetadados==null){

            $scope.processandoSalvar = true;
            //console.log($scope.serie);
            $http.post("cms/alterar-serie/"+$scope.id, {'serie': $scope.serie, 'removerImagem': $scope.removerImagem, 'removerArquivo': $scope.removerArquivo, 'removerArquivoMetadados': $scope.removerArquivoMetadados}).success(function (data){
                //console.log(data);
                $scope.processandoSalvar = false;
                $scope.mensagemSalvar = data;
                $scope.removerImagem = 0;
                $scope.removerArquivo = 0;
            }).error(function(data){
                //console.log(data);
                $scope.mensagemSalvar = "Ocorreu um erro: "+data;
                $scope.processandoSalvar = false;
            });

        }else{

            Upload.upload({
                url: 'cms/alterar-serie/'+$scope.id,
                data: {serie: $scope.serie, file: file, arquivo:arquivo, arquivoMetadados:arquivoMetadados, removerImagem: $scope.removerImagem, removerArquivo: $scope.removerArquivo, removerArquivoMetadados: $scope.removerArquivoMetadados},
            }).then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                });
                $scope.picFile = null;//limpa o form
                $scope.mensagemSalvar =  "Gravado com sucesso!";
                $scope.removerImagem = 0;
                $scope.removerArquivo = 0;
                $scope.removerArquivoMetadados = 0;
                $scope.imagemBD = 'imagens/series/'+response.data;
                //console.log($scope.imagemDB);
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

    $scope.limparArquivo= function(){
        $scope.fileArquivo = null;
        $scope.arquivoBD = null;
        $scope.removerArquivo = 1;
    };

    $scope.limparArquivoMetadados= function(){
        $scope.fileArquivoMetadados = null;
        $scope.arquivoMetadadosBD = null;
        $scope.removerArquivoMetadados = 1;
    };

    $scope.carregaImagem  = function(img, arquivo, arquivoMetadados) {
        if(img!=''){
            $scope.imagemBD = 'imagens/series/xs-'+img;
            //console.log($scope.imagemBD);
        }
        if(arquivo!=''){
            $scope.arquivoBD = arquivo;
            //console.log($scope.baseBD);
        }
        if(arquivoMetadados!=''){
            $scope.arquivoMetadadosBD = arquivoMetadados;
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

    $scope.setTipoDados = function(){

        let tipos = {
            tipo_territorios: $scope.tipo_territorios,
            tipo_pontos: $scope.tipo_pontos,
            tipo_arquivo: $scope.tipo_arquivo,
        };

        console.log(tipos);

        if(tipos.tipo_territorios && !tipos.tipo_pontos && !tipos.tipo_arquivo){
            $scope.serie.tipo_dados = "0";
            console.log($scope.serie.tipo_dados);
            return;
        }
        if(!tipos.tipo_territorios && tipos.tipo_pontos && !tipos.tipo_arquivo){
            $scope.serie.tipo_dados =  "1";
            console.log($scope.serie.tipo_dados);
            return;
        }
        if(tipos.tipo_territorios && tipos.tipo_pontos && !tipos.tipo_arquivo){
            $scope.serie.tipo_dados =  "2";
            console.log($scope.serie.tipo_dados);
            return;
        }
        if(!tipos.tipo_territorios && !tipos.tipo_pontos && tipos.tipo_arquivo){
            $scope.serie.tipo_dados =  "3";
            console.log($scope.serie.tipo_dados);
            return;
        }
        if(tipos.tipo_territorios && !tipos.tipo_pontos && tipos.tipo_arquivo){
            $scope.serie.tipo_dados =  "4";
            console.log($scope.serie.tipo_dados);
            return;
        }
        if(!tipos.tipo_territorios && tipos.tipo_pontos && tipos.tipo_arquivo){
            $scope.serie.tipo_dados =  "5";
            console.log($scope.serie.tipo_dados);
            return;
        }
        if(tipos.tipo_territorios && tipos.tipo_pontos && tipos.tipo_arquivo){
            $scope.serie.tipo_dados =  "6";
            console.log($scope.serie.tipo_dados);
            return;
        }

        $scope.serie.tipo_dados =  0;
    };





}]);
