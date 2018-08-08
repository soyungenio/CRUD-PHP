<?php
require_once '../auth.php';
authAPI();

include 'project.php';
$obj=new Project();
$result=$obj->delete_Project_info_by_id($_GET['Project_id']);
$message['message']=$result;
echo json_encode($message);
?>