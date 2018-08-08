<?php

include 'db_helper.php';



$obj=new Project();
$img_list=$obj->get_project_imgs($_GET['Project_id']);


echo(json_encode($img_list));

?>