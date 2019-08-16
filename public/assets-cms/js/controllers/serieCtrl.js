cmsApp.controller('serieCtrl', ['$scope', '$http', 'Upload', '$timeout', function($scope, $http, Upload, $timeout){


    $scope.tipoDados = 0;
    $scope.abrangenciaLimpar = 0;
    $scope.ano_pontos = 0;

    $scope.series = [];
    $scope.currentPage = 1;
    $scope.lastPage = 0;
    $scope.totalItens = 0;
    $scope.maxSize = 5;
    $scope.itensPerPage = 10;
    $scope.dadoPesquisa = '';
    $scope.campos = "series.id, textos_series.titulo";
    $scope.campoPesquisa = "textos_series.titulo";
    $scope.processandoListagem = false;
    $scope.processandoExcluir = false;
    $scope.ordem = "textos_series.titulo";
    $scope.sentidoOrdem = "asc";
    var $listar = false;//para impedir de carregar o conteúdo dos watchs no carregamento da página.

    $scope.$watch('currentPage', function(){
        if($listar){
            listarSeries();
        }
    });
    $scope.$watch('itensPerPage', function(){
        if($listar){
            listarSeries();
        }
    });
    $scope.$watch('dadoPesquisa', function(){
        if($listar){
            listarSeries();
        }
    });

    var listarSeries = function(){
        $scope.processandoListagem = true;
        $http({
            url: 'cms/listar-series',
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
            $scope.series = data.data;
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
     url: '/api/series/'+$scope.itensPerPage,
     method: 'GET',
     params: {page:  $scope.currentPage}
     }).success(function (data, status, headers, config) {
     $scope.lastPage = data.last_page;
     $scope.totalItens = data.total;

     console.log("total: "+$scope.totalItens);
     console.log("lastpage: "+$scope.lastPage);
     console.log("currentpage: "+$scope.currentPage);

     $scope.series = data.data;

     //$scope.series = $scope.series.concat(data.data);

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

        listarSeries();
    };

    $scope.validar = function(){

    };
    

    listarSeries();

    //INSERIR/////////////////////////////

    $scope.tinymceOptions = tinymceOptions;    

    $scope.mostrarForm = false;

    $scope.processandoInserir = false;

    $scope.inserir = function (file){

        $scope.mensagemInserir = "";

        let tipos = {
            tipo_territorios: $scope.tipo_territorios,
            tipo_pontos: $scope.tipo_pontos,
            tipo_arquivo: $scope.tipo_arquivo,
        };

        $scope.serie.tipo_dados = $scope.getTipoDados(tipos);

        if(file==null){
            $scope.processandoInserir = true;

            //console.log($scope.serie);
            $http.post("cms/inserir-serie", {serie: $scope.serie, textos: $scope.textos}).success(function (data){
                 listarSeries();
                 //delete $scope.serie;//limpa o form
                delete $scope.textos.titulo;
                delete $scope.textos.descricao;
                $scope.mensagemInserir =  "Gravado com sucesso!";
                $scope.processandoInserir = false;
             }).error(function(data){
                $scope.mensagemInserir = "Ocorreu um erro!";
                $scope.processandoInserir = false;
             });
        }else{
            file.upload = Upload.upload({
                url: 'cms/inserir-serie',
                data: {serie: $scope.serie, textos: $scope.textos, file: file},
            });

            file.upload.then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                });
                //delete $scope.serie;//limpa o form
                delete $scope.textos.titulo;
                delete $scope.textos.descricao;
                $scope.picFile = null;//limpa o file
                listarSeries();
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

    $scope.getTipoDados = function(tipos){
        if(tipos.tipo_territorios && !tipos.tipo_pontos && !tipos.tipo_arquivo){
            return 0;
        }
        if(!tipos.tipo_territorios && tipos.tipo_pontos && !tipos.tipo_arquivo){
            return 1;
        }
        if(tipos.tipo_territorios && tipos.tipo_pontos && !tipos.tipo_arquivo){
            return 2;
        }
        if(!tipos.tipo_territorios && !tipos.tipo_pontos && tipos.tipo_arquivo){
            return 3;
        }
        if(tipos.tipo_territorios && !tipos.tipo_pontos && tipos.tipo_arquivo){
            return 4;
        }
        if(!tipos.tipo_territorios && tipos.tipo_pontos && tipos.tipo_arquivo){
            return 5;
        }
        if(tipos.tipo_territorios && tipos.tipo_pontos && tipos.tipo_arquivo){
            return 6;
        }

        return 0;
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
    };

    $scope.excluir = function(id){
        $scope.processandoExcluir = true;
        $http({
            url: 'cms/excluir-serie/'+id,
            method: 'GET'
        }).success(function(data, status, headers, config){
            console.log(data);
            $scope.processandoExcluir = false;
            $scope.excluido = true;
            $scope.mensagemExcluido = "Excluído com sucesso!";
            listarSeries();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoExcluir = false;
            $scope.mensagemExcluido = "Erro ao tentar excluir!";
        });
    };
    //////////////////////////////////


    //LIMPAR VALORES///////////////////////////
    $scope.perguntaLimparValores = function (id, titulo, imagem){
        $scope.idLimparValores = id;
        $scope.tituloLimparValores = titulo;
        $scope.mensagemExcluidoValores = "";
    };

    $scope.limpar = function(id){
        $scope.processandoLimparValores = true;
        $http({
            url: 'cms/limpar-valores-serie/'+id+'/'+$scope.abrangenciaLimpar+'/'+$scope.tipoDados+'/'+$scope.ano_pontos,
            method: 'GET'
        }).success(function(data, status, headers, config){
            console.log(data);
            $scope.processandoLimparValores = false;
            $scope.mensagemExcluidoValores = "Excluído com sucesso!";
            listarSeries();
        }).error(function(data){
            $scope.message = "Ocorreu um erro: "+data;
            $scope.processandoLimpar = false;
            $scope.mensagemExcluido = "Erro ao tentar limpar!";
        });
    };
    ////////////////////////////////////////////

}]);
