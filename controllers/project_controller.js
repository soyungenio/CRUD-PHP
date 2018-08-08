//Reference File app.js
myApp.controller('project_controller', function ($scope, $state, $http, $location) {
    var vm = this;

    $scope.currentPage = 1;
    $scope.maxSize = 3;
    vm.Project = {};
    vm.Image_info = [];
    vm.delImgsDb = [];
    
    $scope.imageData = {co: [], img:[], descrip: [], type: []};
    $scope.files = {img:[]};
    $scope.uploadedFile = function(element) {
        $scope.files.img[element.id] = element.files[0];
        $scope.imageData.img[element.id] = element.files[0]['name'];
    }
    $scope.uploadPreview = function(element) {
        $scope.imageData.img[element.id] = element.files[0]['name'];
        $scope.imageData.type[element.id] = "preview";
        $scope.imageData.descrip[element.id] = "preview";
        $scope.files.img[element.id] = element.files[0];
        vm.Project.preview = element.files[0]['name'];
    }
    $scope.addNewChoice = function () {
        $scope.imageData.co.push('');
    };
    $scope.deleteImg = function (arrayIndex, indexindb, img) {
        vm.Image_info.splice(arrayIndex, 1);
        vm.delImgsDb.push([indexindb, img]);
    };
    function insertimgs(project_id) {
        return $http({
            method  : 'POST',
            url     : './crud/php/images/insertimg.php',

            processData: false,
            transformRequest: function (data) {
                var formData = new FormData();
                for (var i = 0; i < $scope.files.img.length; i++) {
                    if($scope.files.img[i] != null)
                        formData.append('image[]', $scope.files.img[i]);
                }
                if($scope.imageData.img[0] == null){
                    $scope.imageData.img.shift();
                    $scope.imageData.type.shift();
                    $scope.imageData.descrip.shift();
                }
                formData.append('data', JSON.stringify($scope.imageData));
                formData.append('project_id', project_id);
                return formData;  
            },  

            data : $scope.files,
            headers: {
                    'Content-Type': undefined
            }
        });
    };
    this.loadData = function (page_number) {
        var search_input = document.getElementById("search_input").value;
        $http.get('crud/php/select.php?page=' + page_number + '&search_input=' + search_input).then(function (response) {
            vm.Project_list = response.data.Project_data;
            for (var prop=0 in vm.Project_list) {
                vm.Project_list[prop] = JSON.parse(vm.Project_list[prop]);
              }
            $scope.total_row = response.data.total;
        });
    };

    $scope.$watch('currentPage + numPerPage', function () {
        vm.loadData($scope.currentPage);
        var begin = (($scope.currentPage - 1) * $scope.numPerPage)
                , end = begin + $scope.numPerPage;
    });
//    

    this.addProject = function (info) {
        $scope.img_project_id = 0;
        $http.post('crud/php/insert.php', this.Project).then(function (response) {
            $scope.img_project_id = parseInt(response.data);
            vm.alert_class = 'custom-alert';

            insertimgs($scope.img_project_id).then(function successCallback(response) {
                console.log(response.data);
    
                document.getElementById("create_Project_info_frm").reset();
                $('#create_Project_info_modal').modal('toggle');
                vm.loadData($scope.currentPage);
    
                $scope.imageData = {co: [], img:[], descrip: [], type: []};
                $scope.files = {img:[]};
    
                }, function errorCallback(response) {
            });
        });

    };

    this.edit_Project_info = function (Project_id) {
        $http.get('crud/php/selectone.php?Project_id=' + Project_id).then(function (response) {
            vm.Project_info = response.data;
        });       
        $http.get('crud/php/images/selectimg.php?Project_id=' + Project_id).then(function (response) {
            vm.Image_info = response.data;
            console.log("fff");
        });
    };


    this.updateProject = function () {
        $http.put('crud/php/update.php', this.Project_info).then(function (response) {
            vm.msg = response.data.message;
            vm.alert_class = 'custom-alert';
        });
        for (var i = 0; i < vm.delImgsDb.length; i++) {
            var url = './crud/php/images/deleteimg.php?img_id='+vm.delImgsDb[i][0]+'&img='+vm.delImgsDb[i][1]+'&project_img_id='+this.Project_info.id;
            $http.delete(url).then(function (response) {
                vm.msg = response.data.message;
                console.log(vm.delImgsDb[i]+'\n');
            });
        }
        if($scope.imageData.img.length>0)
            insertimgs(this.Project_info.id).then(function successCallback(response) {
                console.log(response.data);

                $('#edit_Project_info_modal').modal('toggle');
                vm.loadData($scope.currentPage);

                $scope.imageData = {co: [], img:[], descrip: [], type: []};
                $scope.files = {img:[]};

                }, function errorCallback(response) {
            });
        else
            $('#edit_Project_info_modal').modal('toggle');
            vm.loadData($scope.currentPage);     
    };



    this.delete_Project_info = function (Project_id) {
        $http.delete('crud/php/delete.php?Project_id=' + Project_id).then(function (response) {
            vm.msg = response.data.message;
            vm.alert_class = 'custom-alert';

            $http.delete('./crud/php/images/deleteimg.php?project_id=' + Project_id).then(function (response) {
                vm.msg = response.data.message;
            });
            vm.loadData($scope.currentPage);
        });

    };
});

