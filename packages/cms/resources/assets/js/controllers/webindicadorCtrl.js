cmsApp.controller('webindicadorCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){

    $scope.webindicadores = [];
    $scope.currentPage = 1;
    $scope.lastPage = 0;
    $scope.totalItens = 0;
    $scope.maxSize = 5;
    $scope.itensPerPage = 10;
    $scope.dadoPesquisa = '';
    $scope.campos = "id, titulo, idioma_sigla, posicao, status";
    $scope.campoPesquisa = "titulo";
    $scope.processandoListagem = false;
    $scope.processandoExcluir = false;
    $scope.ordem = "posicao";
    $scope.sentidoOrdem = "asc";
    var $listar = false;//para impedir de carregar o conteúdo dos watchs no carregamento da página.

    $scope.$watch('currentPage', function(){
        if($listar){
            listarWebindicadores();
        }
    });
    $scope.$watch('itensPerPage', function(){
        if($listar){
            listarWebindicadores();
        }
    });
    $scope.$watch('dadoPesquisa', function(){
        if($listar){
            listarWebindicadores();
        }
    });

    var listarWebindicadores = function(){
        $scope.processandoListagem = true;
        $http({
            url: 'cms/listar-webindicadores',
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
            $scope.webindicadores = data.data;
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


    $scope.ordernarPor = function(ordem){
        $scope.ordem = ordem;
        //console.log($scope.ordem);
        if($scope.sentidoOrdem=="asc"){
            $scope.sentidoOrdem = "desc";
        }else{
            $scope.sentidoOrdem = "asc";
        }

        listarWebindicadores();
    };

    $scope.validar = function(){

    };
    

    listarWebindicadores();

    //INSERIR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;
    $scope.mostrarForm = false;
    $scope.processandoInserir = false;

    $scope.inserir = function (file, arquivo){

        $scope.mensagemInserir = "";

        if(file==null && arquivo==null){
            $scope.processandoInserir = true;

            //console.log($scope.webindicador);
            $http.post("cms/inserir-webindicador", {webindicador: $scope.webindicador}).success(function (data){
                 listarWebindicadores();
                 delete $scope.webindicador;//limpa o form
                $scope.mensagemInserir =  "Gravado com sucesso!";
                $scope.processandoInserir = false;
             }).error(function(data){
                $scope.mensagemInserir = "Ocorreu um erro!";
                $scope.processandoInserir = false;
             });
        }else{
            Upload.upload({
                url: 'cms/inserir-webindicador',
                data: {webindicador: $scope.webindicador, file: file, arquivo: arquivo},
            }).then(function (response) {
                    $timeout(function () {
                        $scope.result = response.data;
                    });
                    console.log(response.data);
                    delete $scope.webindicador;//limpa o form
                    $scope.picFile = null;//limpa o file
                    $scope.fileArquivo = null;//limpa o file
                    listarWebindicadores();
                    $scope.mensagemInserir =  "Gravado com sucesso!";
            }, function (response) {
                console.log(response.data);
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
            url: 'cms/excluir-webindicador/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            console.log(data);
            $scope.processandoExcluir = false;
            $scope.excluido = true;
            $scope.mensagemExcluido = "Excluído com sucesso!";
            listarWebindicadores();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoExcluir = false;
            $scope.mensagemExcluido = "Erro ao tentar excluir!";
        });
    };
    //////////////////////////////////
    $scope.positionUp = function(id){
        $scope.idPositionUp = '';
        $http({
            url: 'cms/positionUp-webindicador/'+id,
            method: 'GET'
        }).success(function(data, positionUp, headers, config){
            $scope.idPositionUp = id;
            listarWebindicadores();
        });
    };
    $scope.positionDown = function(id){
        $scope.idPositionDown = '';
        $http({
            url: 'cms/positionDown-webindicador/'+id,
            method: 'GET'
        }).success(function(data, positionDown, headers, config){
            $scope.idPositionDown = id;
            listarWebindicadores();
        });
    };
    /////////////////////////////////////
    $scope.status = function(id){
        //console.log(id);
        $scope.mensagemStatus = '';
        $scope.idStatus = '';
        $scope.processandoStatus = true;
        $http({
            url: 'cms/status-webindicador/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            $scope.processandoStatus = false;
            $scope.mensagemStatus = 'color-success';
            $scope.idStatus = id;
            listarWebindicadores();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoStatus = false;
            $scope.mensagemStatus = "Erro ao tentar status!";
        });
    };

}]);
