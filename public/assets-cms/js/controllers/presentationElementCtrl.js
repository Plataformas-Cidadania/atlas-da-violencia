cmsApp.controller('presentationElementCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){
    
    $scope.elements = [];
    $scope.currentPage = 1;
    $scope.lastPage = 0;
    $scope.totalItens = 0;
    $scope.maxSize = 5;
    $scope.itensPerPage = 10;
    $scope.dadoPesquisa = '';
    $scope.campos = "id, type, chart_type, language, row, position, status";
    $scope.campoPesquisa = "type";
    $scope.processandoListagem = false;
    $scope.processandoExcluir = false;
    $scope.ordem = "type";
    $scope.sentidoOrdem = "asc";
    $scope.types = [];
    $scope.chartTypes = [];
    $scope.status = [];
    $scope.fileArquivo = null;
    $scope.picFile = null;
    var $listar = false;//para impedir de carregar o conteúdo dos watchs no carregamento da página.

    $scope.$watch('currentPage', function(){
        if($listar){
            listarItems();
        }
    });
    $scope.$watch('itensPerPage', function(){
        if($listar){
            listarItems();
        }
    });
    $scope.$watch('dadoPesquisa', function(){
        if($listar){
            listarItems();
        }
    });

    $scope.$watch('element.presentation_id', function(){
        listarItems();
    });

    var listarItems = function(){
        $scope.processandoListagem = true;
        $http({
            url: 'cms/listar-presentation-elements',
            method: 'GET',
            params: {
                page: $scope.currentPage,
                itensPorPagina: $scope.itensPerPage,
                dadoPesquisa: $scope.dadoPesquisa,
                campos: $scope.campos,
                campoPesquisa: $scope.campoPesquisa,
                ordem: $scope.ordem,
                sentido: $scope.sentidoOrdem,
                presentation_id: $scope.element.presentation_id
            }
        }).success(function(data, status, headers, config){
            $scope.elements = data.elements.data;
            $scope.lastPage = data.elements.last_page;
            $scope.totalItens = data.elements.total;
            $scope.primeiroDaPagina = data.elements.from;
            $scope.ultimoDaPagina = data.elements.to;
            $scope.types = data.types;
            $scope.chartTypes = data.chart_types;
            $scope.status = data.status;
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

        listarItems();
    };

    $scope.validar = function(){

    };

    //listarItems();

    //INSERIR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;
    $scope.mostrarForm = false;
    $scope.processandoInserir = false;

    $scope.inserir = function (file, arquivo){

        $scope.mensagemInserir = "";

        console.log(file);
        console.log(arquivo);

        if(file==null && arquivo==null){
            $scope.processandoInserir = true;

            //console.log($scope.element);
            $http.post("cms/inserir-presentation-element", {element: $scope.element}).success(function (data){
                 listarItems();
                //delete $scope.element;//limpa o form
                //deleta um por um para não excluir o id da tabela relacionada
                $scope.element.tipo_id = '';
                $scope.element.integrante_id = '';
                $scope.mensagemInserir =  "Gravado com sucesso!";
                $scope.processandoInserir = false;
             }).error(function(data){
                $scope.mensagemInserir = "Ocorreu um erro!";
                $scope.processandoInserir = false;
             });
        }else{

            Upload.upload({
                url: 'cms/inserir-presentation-element',
                data: {element: $scope.element, file: file, arquivo: arquivo},
            }).then(function (response) {
                $timeout(function () {
                    $scope.result = response.data;
                });
                console.log(response.data);
                //delete $scope.element;//limpa o form
                //deleta um por um para não excluir o id da tabela relacionada
                $scope.element.tipo_id = '';
                $scope.element.integrante_id = '';
                $scope.picFile = null;//limpa o file
                $scope.fileArquivo = null;//limpa o file
                listarItems();
                $scope.mensagemInserir =  "Gravado com sucesso!";
            }, function (response) {
                console.log(response.data);
                if (response.status > 0){
                    $scope.errorMsg = response.status + ': ' + response.data;
                }
            }, function (evt) {
                //console.log(evt);
                // Math.min is to fix IE which reports 200% sometimes
                $scope.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
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
            url: 'cms/excluir-item-versao/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            console.log(data);
            $scope.processandoExcluir = false;
            $scope.excluido = true;
            $scope.mensagemExcluido = "Excluído com sucesso!";
            listarItems();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoExcluir = false;
            $scope.mensagemExcluido = "Erro ao tentar excluir!";
        });
    };
    //////////////////////////////////
    $scope.status = function(id){
        //console.log(id);
        $scope.mensagemStatus = '';
        $scope.idStatus = '';
        $scope.processandoStatus = true;
        $http({
            url: 'cms/status-item-versao/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            //console.log(data);
            $scope.processandoStatus = false;
            //$scope.excluido = true;
            $scope.mensagemStatus = 'color-success';
            $scope.idStatus = id;
            listarItems();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoStatus = false;
            $scope.mensagemStatus = "Erro ao tentar status!";
        });
    };

}]);
