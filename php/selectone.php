<?php
include 'project.php';
$obj=new Project();
$Project_data=$obj->view_Project_by_Project_id($_GET['Project_id']);
echo $Project_data;


?>
