ipeaApp.controller('serieCtrl', ['$scope', '$http', '$filter', function($scope, $http, $filter){

    $scope.dates = {};
    $scope.datesRange = {};
    $scope.series = {};
    $scope.range = '';
    $scope.valores = {};
    $scope.min = [];
    $scope.max = [];
    $scope.media = [];
    $scope.ponderada = [];
    $scope.moda = [];
    $scope.mediana = [];
    $scope.cores = {};
    //$scope.orderDate = true;

    /*$scope.$watch('orderDate', function(){
        $scope.datesRange.sort(function(a,b){
            return b - a;
        });
    });*/

    $scope.initDates = function(){

    };

    $scope.filterDates = function(chart_dates, chart_values){
        var dates_range = {};
        $scope.datesRange = {};
        for(date in $scope.dates){
            var date = parseInt(date);
            if(jQuery.inArray(date, chart_dates) > -1){
                if(!dates_range[date]){
                    dates_range[date] = [];
                }
                dates_range[date] = $scope.dates[date];
            }
        }
        $scope.datesRange = dates_range;
        console.log('###', $scope.datesRange);
        $scope.calculos(chart_values);
    };

    var listarSeries = function(){

        //$scope.processandoListagem = true;
        $http({
            url: '/listar-series/',
            method: 'GET',
            params: {

            }
        }).success(function(data, status, headers, config){
            //$scope.processandoListagem = false;

            $scope.series = data.series;
            var valores = data.valores;
            var chart_values = [];

            valores.forEach(function(valor){
                if(!$scope.dates[valor.data]){
                    $scope.dates[valor.data] = [];
                    $scope.datesRange[valor.data] = [];
                }
                if(!chart_values[valor.id_serie]){
                    chart_values[valor.id_serie] = [];
                }
                $scope.dates[valor.data][valor.id_serie] = valor;
                $scope.dates[valor.data]['date'] = valor.data;

                $scope.datesRange[valor.data][valor.id_serie] = valor;
                $scope.datesRange[valor.data]['date'] = valor.data;
                chart_values[valor.id_serie].push(valor.valor);
                //console.log(valor);
            });

            console.log($scope.datesRange);

            $scope.calculos(chart_values);

        }).error(function(data){
            //$scope.message = "Ocorreu um erro: "+data;
            //$scope.processandoListagem = false;
        });
    };

    listarSeries();


    $scope.intervalo = function(start, end){
        $scope.range = start+';'+end;
    };

    $scope.aplicarCores = function(cores){
        $scope.cores = cores;
    };


    $scope.calculos = function(chart_values){
        //console.log(chart_values);
        for(var i in chart_values){

            for(j in chart_values[i]){
                if(chart_values[i][j]===null){
                    chart_values[i].splice(j, 1);
                }
            }

        }
        //console.log(chart_values);

        for(var i in chart_values){
            $scope.calcMinMax(i, chart_values[i]);
            $scope.calcMedia(i, chart_values[i]);
            $scope.calcModa(i, chart_values[i]);
            $scope.calcMediana(i, chart_values[i]);
            $scope.calcPonderada(i, chart_values[i]);
        }
    };

    $scope.calcMinMax = function(id_serie, serie_original){
        var serie = angular.copy(serie_original);
        var min = serie[0];
        var max = serie[0];
        for(var i in serie){
            if(serie[i] < min){
                min = serie[i];
            }
            if(serie[i] > max){
                max = serie[i];
            }
        }
        $scope.min[id_serie] = min;
        $scope.max[id_serie] = max;
    };

    $scope.calcMedia = function(id_serie, serie_original){
        var serie = angular.copy(serie_original);
        var total = 0;
        for(var i in serie){
            total += serie[i];
        }

        $scope.media[id_serie] = total/serie.length;
    };

    $scope.calcModa = function(id_serie, serie_original){
        var serie = angular.copy(serie_original);
        var numeros = {};
        for(var i in serie){
            if(!numeros[serie[i]]){
                numeros[serie[i]] = 0;
            }
            numeros[serie[i]]++;
        }
        var moda = 0;
        var qtd_moda = 0;
        for(var i in numeros){
            //console.log(i);
            if(numeros[i] > qtd_moda){
                moda = i;
                qtd_moda = numeros[i];
            }
        }
        if(qtd_moda == 1){
            $scope.moda[id_serie] = [];
            return;
        }
        var array_moda = [moda];
        for(var i in numeros){
            //console.log(i);
            if(numeros[i] == qtd_moda && i != moda){
                array_moda.push(i);
            }
        }
        for(var i in array_moda){
           array_moda[i] = $filter('number')(array_moda[i], 3)
        }
        $scope.moda[id_serie] = array_moda.join(' - ');
    };

    $scope.calcMediana = function(id_serie, serie_original){
        var serie = angular.copy(serie_original);
        serie.sort(function(a, b){
            return a - b;
        });
        var qtd = serie.length;
        if(qtd%2!=0){
            var pos = (qtd-1)/2;
            var mediana = serie[pos];
            $scope.mediana[id_serie] = mediana;
            return;
        }
        var pos = qtd/2;
        var mediana = (serie[pos]+serie[pos-1])/2;
        $scope.mediana[id_serie] = mediana;
    }

    $scope.calcPonderada = function(id_serie, serie_original){
        var serie = angular.copy(serie_original);
        var total_indices = 0;
        var total_valores = 0;
        var qtd = serie.length;
        for(var i=0;i<qtd;i++){
            total_indices += i+1;
            //console.log(total_indices);
            total_valores += serie[i]*(i+1);
        }
        $scope.ponderada[id_serie] = total_valores/total_indices;
    }

}]);
