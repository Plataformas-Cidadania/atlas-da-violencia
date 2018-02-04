ipeaApp.controller('linkCtrl', ['$scope', function($scope){

    $scope.tagSelecionada = '';
    $scope.boxTags = 0;
    $scope.cont = 0;

    $scope.$watch('searchTag', function(){
        $scope.boxTags = 1;
        if($scope.searchTag==''){
            $scope.tagSelecionada = '';
            $scope.boxTags = 0;
            $scope.searchTag = null;
        }

    });


    
    
    $scope.searchLinks = function (tag){
        $scope.tagSelecionada = tag;
        //$scope.searchTag = tag;
        $scope.boxTags = 0;
        
    };

}]);