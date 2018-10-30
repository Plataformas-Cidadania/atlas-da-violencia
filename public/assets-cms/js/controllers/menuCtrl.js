cmsApp.controller('menuCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){
    

    $scope.menus = [];
    $scope.currentPage = 1;
    $scope.lastPage = 0;
    $scope.totalItens = 0;
    $scope.maxSize = 5;
    $scope.itensPerPage = 10;
    $scope.dadoPesquisa = '';
    $scope.campos = "id, title, imagem, idioma_sigla, status, posicao";
    $scope.campoPesquisa = "title";
    $scope.processandoListagem = false;
    $scope.processandoExcluir = false;
    $scope.ordem = "title";
    $scope.sentidoOrdem = "asc";
    var $listar = false;//para impedir de carregar o conteúdo dos watchs no carregamento da página.

    $scope.$watch('currentPage', function(){
        if($listar){
            listarMenus();
        }
    });
    $scope.$watch('itensPerPage', function(){
        if($listar){
            listarMenus();
        }
    });
    $scope.$watch('dadoPesquisa', function(){
        if($listar){
            listarMenus();
        }
    });

    var listarMenus = function(){
        $scope.processandoListagem = true;
        $http({
            url: 'cms/listar-menus',
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
            $scope.menus = data.data;
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
     url: '/api/menus/'+$scope.itensPerPage,
     method: 'GET',
     params: {page:  $scope.currentPage}
     }).success(function (data, status, headers, config) {
     $scope.lastPage = data.last_page;
     $scope.totalItens = data.total;

     console.log("total: "+$scope.totalItens);
     console.log("lastpage: "+$scope.lastPage);
     console.log("currentpage: "+$scope.currentPage);

     $scope.menus = data.data;

     //$scope.menus = $scope.menus.concat(data.data);

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

        listarMenus();
    };

    $scope.validar = function(){

    };
    

    listarMenus();

    //INSERIR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;    

    $scope.mostrarForm = false;

    $scope.processandoInserir = false;

    $scope.inserir = function (file){

        $scope.mensagemInserir = "";

        if(file==null){
            $scope.processandoInserir = true;

            //console.log($scope.menu);
            $http.post("cms/inserir-menu", {menu: $scope.menu}).success(function (data){
                 listarMenus();
                 delete $scope.menu;//limpa o form
                $scope.mensagemInserir =  "Gravado com sucesso!";
                $scope.processandoInserir = false;
             }).error(function(data){
                $scope.mensagemInserir = "Ocorreu um erro!";
                $scope.processandoInserir = false;
             });
        }else{
            file.upload = Upload.upload({
                url: 'cms/inserir-menu',
                data: {menu: $scope.menu, file: file},
            });

            file.upload.then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                });
                delete $scope.menu;//limpa o form
                $scope.picFile = null;//limpa o file
                listarMenus();
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
            url: 'cms/excluir-menu/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            console.log(data);
            $scope.processandoExcluir = false;
            $scope.excluido = true;
            $scope.mensagemExcluido = "Excluído com sucesso!";
            listarMenus();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoExcluir = false;
            $scope.mensagemExcluido = "Erro ao tentar excluir!";
        });
    };
    //////////////////////////////////

    //////////////////////////////////
    $scope.status = function(id){
        //console.log(id);
        $scope.mensagemStatus = '';
        $scope.idStatus = '';
        $scope.processandoStatus = true;
        $http({
            url: 'cms/status-menu/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            //console.log(data);
            $scope.processandoStatus = false;
            //$scope.excluido = true;
            $scope.mensagemStatus = 'color-success';
            $scope.idStatus = id;
            listarMenus();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoStatus = false;
            $scope.mensagemStatus = "Erro ao tentar status!";
        });
    };
    $scope.positionUp = function(id){
        $scope.idPositionUp = '';
        $http({
            url: 'cms/positionUp-menu/'+id,
            method: 'GET'
        }).success(function(data, positionUp, headers, config){
            $scope.idPositionUp = id;
            listarMenus();
        });
    };
    $scope.positionDown = function(id){
        $scope.idPositionDown = '';
        $http({
            url: 'cms/positionDown-menu/'+id,
            method: 'GET'
        }).success(function(data, positionDown, headers, config){
            $scope.idPositionDown = id;
            listarMenus();
        });
    };

}]);
