<?php
include '../db.php';
class Project
{
    private $conn;
    function __construct() {
      $this->conn = db::connect_db();
    }

    public function insert_dataimg($project_id, $post_data=array()){

        $img='';
        if(isset($post_data['img']))
          $img = $post_data['img'];
        
        $descrip='';
        if(isset($post_data['descrip']))
          $descrip = $post_data['descrip'];

        $type='';
          if(isset($post_data['type']))
            $type = $post_data['type'];

        $sql="INSERT INTO Images (project_id,img,descrip,type) VALUES (?, ?, ?, ?)";
          
        $stmt = $this->conn->prepare($sql);
        if(!$stmt ){ 
            die( "SQL Error: {$this->conn->errno} - {$this->conn->error}" );
        }
        $stmt->bind_Param("isss", $project_id, $img, $descrip, $type);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if($res){
          return 'Succefully Created image Info';     
        }else{
            return 'An error occurred while inserting data';     
        } 
    }
    public function delete_project_imgs($project_id){
        $sql="Delete from Images where project_id = ?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt ){ 
            die( "SQL Error: {$this->conn->errno} - {$this->conn->error}" );
        }
        $stmt->bind_Param("i", $project_id);
        $stmt->execute();
        $res = $stmt->get_result();

        $sql="ALTER TABLE Images AUTO_INCREMENT = 1";
        $this->conn->query($sql);

        if($result){
            return 'Succefully deleted Project Info';     
          }else{
              return 'An error occurred while deleting data';     
          } 
    }
    public function delete_imgs_by_id($id){
        $sql="Delete from Images where id = ?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt ){ 
            die( "SQL Error: {$this->conn->errno} - {$this->conn->error}" );
        }
        $stmt->bind_Param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        $sql="ALTER TABLE Images AUTO_INCREMENT = 1";
        $this->conn->query($sql);

        if($result){
            return 'Succefully deleted Project Info';     
          }else{
              return 'An error occurred while deleting data';     
          } 
    }
    public function get_project_imgs($project_id){
        if(isset($project_id)){
            $Project_id= mysqli_real_escape_string($this->conn,trim($project_id));
            
            $sql="Select * from Images where project_id='$Project_id'";
                
            $result=  $this->conn->query($sql);

            $Project=array();
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                //$Project["Project_data"][]= json_encode($this->to_utf8($row), JSON_UNESCAPED_UNICODE);
                $Project[]= $row;
              }
            }else{
              echo("Что-то пошло не так ");
            }

            return $Project;
        }  
    }
}