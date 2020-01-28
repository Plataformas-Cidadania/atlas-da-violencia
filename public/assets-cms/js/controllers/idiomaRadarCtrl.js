cmsApp.controller('idiomaRadarCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){
    

    $scope.radares = [];
    $scope.radar_id = 0;
    $scope.currentPage = 1;
    $scope.lastPage = 0;
    $scope.totalItens = 0;
    $scope.maxSize = 5;
    $scope.itensPerPage = 10;
    $scope.dadoPesquisa = '';
    $scope.campos = "id, titulo, idioma_sigla";
    $scope.campoPesquisa = "titulo";
    $scope.processandoListagem = false;
    $scope.processandoExcluir = false;
    $scope.ordem = "titulo";
    $scope.sentidoOrdem = "asc";
    var $listar = false;//para impedir de carregar o conteúdo dos watchs no carregamento da página.

    $scope.$watch('currentPage', function(){
        if($listar){
            listarRadares();
        }
    });
    $scope.$watch('itensPerPage', function(){
        if($listar){
            listarRadares();
        }
    });
    $scope.$watch('dadoPesquisa', function(){
        if($listar){
            listarRadares();
        }
    });

    $scope.setRadarId = function(radar_id){
        $scope.radar_id = radar_id;
        listarRadares();
    };


    var listarRadares = function(){
        $scope.processandoListagem = true;
        $http({
            url: 'cms/listar-idiomas-radares',
            method: 'GET',
            params: {
                page: $scope.currentPage,
                itensPorPagina: $scope.itensPerPage,
                dadoPesquisa: $scope.dadoPesquisa,
                campos: $scope.campos,
                campoPesquisa: $scope.campoPesquisa,
                ordem: $scope.ordem,
                sentido: $scope.sentidoOrdem,
                radar_id: $scope.radar_id
            }
        }).success(function(data, status, headers, config){
            $scope.radares = data.data;
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
     url: '/api/radares/'+$scope.itensPerPage,
     method: 'GET',
     params: {page:  $scope.currentPage}
     }).success(function (data, status, headers, config) {
     $scope.lastPage = data.last_page;
     $scope.totalItens = data.total;

     console.log("total: "+$scope.totalItens);
     console.log("lastpage: "+$scope.lastPage);
     console.log("currentpage: "+$scope.currentPage);

     $scope.radares = data.data;

     //$scope.radares = $scope.radares.concat(data.data);

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

        listarRadares();
    };

    $scope.validar = function(){

    };
    

    //listarRadares();

    //INSERIR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;    

    $scope.mostrarForm = false;

    $scope.processandoInserir = false;

    $scope.inserir = function (file){

        $scope.mensagemInserir = "";

        if(file==null){
            $scope.processandoInserir = true;

            //console.log($scope.radar);
            $http.post("cms/inserir-idioma-radar", {radar: $scope.radar, idiomas: $scope.idiomas}).success(function (data){
                 listarRadares();
                 //delete $scope.radar;//limpa o form
                delete $scope.radar.titulo;
                delete $scope.radar.descricao;
                $scope.mensagemInserir =  "Gravado com sucesso!";
                $scope.processandoInserir = false;
             }).error(function(data){
                $scope.mensagemInserir = "Ocorreu um erro!";
                $scope.processandoInserir = false;
             });
        }else{
            file.upload = Upload.upload({
                url: 'cms/inserir-idioma-radar',
                data: {radar: $scope.radar, idiomas: $scope.idiomas, file: file},
            });

            file.upload.then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                });
                //delete $scope.radar;//limpa o form
                delete $scope.idiomas.titulo;
                delete $scope.idiomas.descricao;
                $scope.picFile = null;//limpa o file
                listarRadares();
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
    $scope.perguntaExcluir = function (id, titulo, imagem){
        $scope.idExcluir = id;
        $scope.tituloExcluir = titulo;
        $scope.imagemExcluir = imagem;
        $scope.excluido = false;
        $scope.mensagemExcluido = "";
    }

    $scope.excluir = function(id){
        $scope.processandoExcluir = true;
        $http({
            url: 'cms/excluir-idioma-radar/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            console.log(data);
            $scope.processandoExcluir = false;
            $scope.excluido = true;
            $scope.mensagemExcluido = "Excluído com sucesso!";
            listarRadares();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoExcluir = false;
            $scope.mensagemExcluido = "Erro ao tentar excluir!";
        });
    };
    //////////////////////////////////

}]);
