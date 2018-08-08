<?php
include 'db_helper.php';

$obj=new Project();
if($_GET['project_id']){
    $res = $obj->delete_project_imgs($_GET['project_id']);
    echo $res."\n";

    $dirname = "../../../img/projects/project".$_GET['project_id'];
    if (file_exists($dirname)) {
        deleteDir($dirname);
        echo "ideleted"."\n";
    }
}
if($_GET['img_id']){
    $res = $obj->delete_imgs_by_id($_GET['img_id']);
    echo $res."\n";

    $dirname = "../../../img/projects/project".$_GET['project_img_id']."/".$_GET['img'];
    if (file_exists($dirname)) {
        unlink($dirname);
        echo "ideleted"."\n";
    }
    $dirname = "../../../img/projects/project".$_GET['project_img_id']."/b/".$_GET['img'];
    if (file_exists($dirname)) {
        unlink($dirname);
        echo "ideleted"."\n";
    }
}
function deleteDir($dirPath) {
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}




?>