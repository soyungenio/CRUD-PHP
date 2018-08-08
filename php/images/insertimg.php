<?php
include 'db_helper.php';
include 'ImageResize.php';

use \Gumlet\ImageResize;

	function reArrayFiles(&$file_post, $keysort) {

		$file_ary = array();
		$file_count = count($file_post[$keysort]);
		$file_keys = array_keys($file_post);

		for ($i=0; $i<$file_count; $i++) {
			foreach ($file_keys as $key) {
			$file_ary[$i][$key] = $file_post[$key][$i];
			}
		}

		return $file_ary;
	}
	if(!empty($_FILES['image'])){
		$file_ary = reArrayFiles($_FILES['image'], 'name');

		$result = (array) json_decode($_POST['data']);
		$file_data = reArrayFiles($result, 'type');

		$fil = "../../../img/projects/project".$_POST['project_id'];
		if (!file_exists($fil)) {
			mkdir($fil, 0777);
		}
		$filb = $fil.'/b';

		if (!file_exists($filb)) {
			mkdir($filb, 0777);
		}
		$index = 0;
		foreach($file_ary as $value){
			$ext = pathinfo($value['name'],PATHINFO_EXTENSION);
			$image = $value['name'];
			move_uploaded_file($value["tmp_name"], $filb.'/'.$image);
			//echo "Image uploaded successfully as ".$image;
			$index++;
		}

		foreach($file_data as $value){
			$imageRes = new ImageResize($filb.'/'.$value['img']);
			if($value['type'] == "preview"){
				echo "preview ".$value['img']."\n";
				$imageRes->resizeToWidth(305);
			}
			else if($value['type'] == "slider"){
				echo "slider ".$value['img']."\n";
				$imageRes->resizeToWidth(515);
			} 
			else if($value['type'] == "plan"){
				echo "plan ".$value['img']."\n";
				$imageRes->resizeToWidth(300);
			}
			$imageRes->save($fil.'/'.$value['img']);
		}

		$obj=new Project();
		foreach($file_data as $value){
			echo "Kak by da no kakby net"."\n";			
			$obj->insert_dataimg($_POST['project_id'], $value);
		}
		echo var_dump($file_data);
	}else{
		echo "_FILES['data']";
	}
?>