cmsApp.controller('optionAbrangenciaCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){


    $scope.optionAbrangencias = [];
    $scope.currentPage = 1;
    $scope.lastPage = 0;
    $scope.totalItens = 0;
    $scope.maxSize = 5;
    $scope.itensPerPage = 10;
    $scope.dadoPesquisa = '';
    //$scope.campos = "optionAbrangencias.id, optionAbrangencias.title";
    $scope.campos = "options_abrangencias.id, idiomas_options_abrangencias.title, idiomas_options_abrangencias.idioma_sigla";
    //$scope.campos = "options_abrangencias.id";
    $scope.campoPesquisa = "idiomas_options_abrangencias.title";
    $scope.processandoListagem = false;
    $scope.processandoExcluir = false;
    $scope.ordem = "idiomas_options_abrangencias.title";
    $scope.sentidoOrdem = "asc";
    var $listar = false;//para impedir de carregar o conteúdo dos watchs no carregamento da página.



    $scope.$watch('currentPage', function(){
        if($listar){
            listarOptionAbrangencias();
        }
    });
    $scope.$watch('itensPerPage', function(){
        if($listar){
            listarOptionAbrangencias();
        }
    });
    $scope.$watch('dadoPesquisa', function(){
        if($listar){
            listarOptionAbrangencias();
        }
    });

    var listarOptionAbrangencias = function(){
        $scope.processandoListagem = true;
        $http({
            url: 'cms/listar-options-abrangencias',
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
            $scope.optionAbrangencias = data.data;
            $scope.lastPage = data.last_page;
            $scope.totalItens = data.total;
            $scope.primeiroDaPagina = data.from;
            $scope.ultimoDaPagina = data.to;
            $listar = true;
            //console.log(data.data);
            $scope.processandoListagem = false;
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoListagem = false;
        });
    };

    /*$scope.loadMore = function() {
     $scope.currentPage +=1;
     $http({
     url: '/api/optionAbrangencias/'+$scope.itensPerPage,
     method: 'GET',
     params: {page:  $scope.currentPage}
     }).success(function (data, status, headers, config) {
     $scope.lastPage = data.last_page;
     $scope.totalItens = data.total;

     console.log("total: "+$scope.totalItens);
     console.log("lastpage: "+$scope.lastPage);
     console.log("currentpage: "+$scope.currentPage);

     $scope.optionAbrangencias = data.data;

     //$scope.optionAbrangencias = $scope.optionAbrangencias.concat(data.data);

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

        listarOptionAbrangencias();
    };

    $scope.validar = function(){

    };


    listarOptionAbrangencias();

    //INSERIR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;

    $scope.mostrarForm = false;

    $scope.processandoInserir = false;

    $scope.inserir = function (file){

        $scope.mensagemInserir = "";

        if(file==null){
            $scope.processandoInserir = true;

            //console.log($scope.optionAbrangencia);
            $http.post("cms/inserir-option-abrangencia", {optionAbrangencia: $scope.optionAbrangencia, idioma: $scope.idioma}).success(function (data){
                 listarOptionAbrangencias();
                 delete $scope.optionAbrangencia;//limpa o form
                 delete $scope.idioma.title;
                 delete $scope.idioma.plural;
                $scope.mensagemInserir =  "Gravado com sucesso!";
                $scope.processandoInserir = false;
             }).error(function(data){
                $scope.mensagemInserir = "Ocorreu um erro!";
                $scope.processandoInserir = false;
             });
        }else{
            file.upload = Upload.upload({
                url: 'cms/inserir-option-abrangencia',
                data: {optionAbrangencia: $scope.optionAbrangencia, idioma: $scope.idioma, file: file},
            });

            file.upload.then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                });
                delete $scope.optionAbrangencia;//limpa o form
                delete $scope.idioma.title;
                delete $scope.idioma.plural;
                $scope.picFile = null;//limpa o file
                listarOptionAbrangencias();
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
    $scope.perguntaExcluir = function (id, title, imagem){
        $scope.idExcluir = id;
        $scope.tituloExcluir = title;
        $scope.imagemExcluir = imagem;
        $scope.excluido = false;
        $scope.mensagemExcluido = "";
    }

    $scope.excluir = function(id){
        $scope.processandoExcluir = true;
        $http({
            url: 'cms/excluir-option-abrangencia/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            console.log(data);
            $scope.processandoExcluir = false;
            $scope.excluido = true;
            $scope.mensagemExcluido = "Excluído com sucesso!";
            listarOptionAbrangencias();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoExcluir = false;
            $scope.mensagemExcluido = "Erro ao tentar excluir!";
        });
    };
    //////////////////////////////////


}]);
