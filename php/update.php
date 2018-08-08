<?php 
require_once '../auth.php';
authAPI();

include 'project.php';
header('Content-Type: text/html; charset=utf-8');
$obj=new Project();
$data = json_decode(file_get_contents("php://input"));
$result=$obj->update_Project_info($data);
$message['message']=$result;
echo var_dump($data);;
//echo json_encode($message);
?>