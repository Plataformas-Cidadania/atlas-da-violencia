cmsApp.controller('temaCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){
    

    $scope.temas = [];
    $scope.currentPage = 1;
    $scope.lastPage = 0;
    $scope.totalItens = 0;
    $scope.maxSize = 5;
    $scope.itensPerPage = 10;
    $scope.dadoPesquisa = '';
    $scope.campos = "id, tema";
    $scope.campoPesquisa = "tema";
    $scope.processandoListagem = false;
    $scope.processandoExcluir = false;
    $scope.ordem = "tema";
    $scope.sentidoOrdem = "asc";
    var $listar = false;//para impedir de carregar o conteúdo dos watchs no carregamento da página.

    $scope.$watch('currentPage', function(){
        if($listar){
            listarTemas();
        }
    });
    $scope.$watch('itensPerPage', function(){
        if($listar){
            listarTemas();
        }
    });
    $scope.$watch('dadoTema', function(){
        if($listar){
            listarTemas();
        }
    });

    var listarTemas = function(){
        $scope.processandoListagem = true;
        $http({
            url: 'cms/listar-temas',
            method: 'GET',
            params: {
                page: $scope.currentPage,
                itensPorPagina: $scope.itensPerPage,
                dadoPesquisa: $scope.dadoPesquisa,
                campos: $scope.campos,
                campoPesquisa: $scope.campoPesquisa,
                ordem: $scope.ordem,
                sentido: $scope.sentidoOrdem
            }
        }).success(function(data, status, headers, config){
            $scope.temas = data.data;
            $scope.lastPage = data.last_page;
            $scope.totalItens = data.total;
            $scope.primeiroDaPagina = data.from;
            $scope.ultimoDaPagina = data.to;
            $listar = true;
            //console.log(data);
            $scope.processandoListagem = false;
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoListagem = false;
        });
    };

    /*$scope.loadMore = function() {
     $scope.currentPage +=1;
     $http({
     url: '/api/temas/'+$scope.itensPerPage,
     method: 'GET',
     params: {page:  $scope.currentPage}
     }).success(function (data, status, headers, config) {
     $scope.lastPage = data.last_page;
     $scope.totalItens = data.total;

     console.log("total: "+$scope.totalItens);
     console.log("lastpage: "+$scope.lastPage);
     console.log("currentpage: "+$scope.currentPage);

     $scope.temas = data.data;

     //$scope.temas = $scope.temas.concat(data.data);

     });
     };*/



    $scope.ordernarPor = function(ordem){
        $scope.ordem = ordem;
        //console.log($scope.ordem);
        if($scope.sentidoOrdem=="asc"){
            $scope.sentidoOrdem = "desc";
        }else{
            $scope.sentidoOrdem = "asc";
        }

        listarTemas();
    };

    $scope.validar = function(){

    };
    

    listarTemas();

    //INSERIR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;    

    $scope.mostrarForm = false;

    $scope.processandoInserir = false;

    $scope.inserir = function (file){

        $scope.mensagemInserir = "";

        if(file==null){
            $scope.processandoInserir = true;

            //console.log($scope.tema);
            $http.post("cms/inserir-tema", {tema: $scope.tema}).success(function (data){
                 listarTemas();
                 delete $scope.tema;//limpa o form
                $scope.mensagemInserir =  "Gravado com sucesso!";
                $scope.processandoInserir = false;
             }).error(function(data){
                $scope.mensagemInserir = "Ocorreu um erro!";
                $scope.processandoInserir = false;
             });
        }else{
            file.upload = Upload.upload({
                url: 'cms/inserir-tema',
                data: {tema: $scope.tema, file: file},
            });

            file.upload.then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                });
                delete $scope.tema;//limpa o form
                $scope.picFile = null;//limpa o file
                listarTemas();
                $scope.mensagemInserir =  "Gravado com sucesso!";
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
        delete $scope.picFile;
        $scope.form.file.$error.maxSize = false;
    };

    $scope.validar = function(valor) {
        //console.log(valor);
        if(valor===undefined){
            return "campo-obrigatorio";
        }
        return "";
    };
    /////////////////////////////////

    //EXCLUIR/////////////////////////
    $scope.perguntaExcluir = function (id, tema, imagem){
        $scope.idExcluir = id;
        $scope.temaExcluir = tema;
        $scope.imagemExcluir = imagem;
        $scope.excluido = false;
        $scope.mensagemExcluido = "";
    }

    $scope.excluir = function(id){
        $scope.processandoExcluir = true;
        $http({
            url: 'cms/excluir-tema/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            console.log(data);
            $scope.processandoExcluir = false;
            $scope.excluido = true;
            $scope.mensagemExcluido = "Excluído com sucesso!";
            listarTemas();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoExcluir = false;
            $scope.mensagemExcluido = "Erro ao tentar excluir!";
        });
    };
    //////////////////////////////////


}]);
