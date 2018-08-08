<?php 
require_once '../auth.php';
authAPI();

include 'project.php';
$obj=new Project();
$data = json_decode(file_get_contents("php://input"));
$result=$obj->create_Project_info($data);
echo $result;
?>