<?php
require_once '../auth.php';
authAPI();

include 'project.php';



$obj=new Project();
$Project_list=$obj->Project_list($_GET['page'],$_GET['search_input']);


echo(json_encode($Project_list));

?>