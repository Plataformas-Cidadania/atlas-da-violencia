ipeaApp.directive('searchMenu', [function(){
    return{
        restrict: 'E',
        templateUrl: '/search-menu-template.html',
        replace: true,
        scope:{
            itemsTitle: "=",
            items: "="
        },
        controller:['$scope', function ($scope) {
            $scope.options = {
                minItens: 3
            };

            console.log($scope.items);

            $scope.indice = function(item){
                for(i in item){
                    return i;
                }
            };
            $scope.searchItems = function(items){

            };
        }]
    }
}]);