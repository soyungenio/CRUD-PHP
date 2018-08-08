<?php
include 'db.php';
class Project
{
    private $conn;
    function __construct() {
      $this->conn = db::connect_db();
    }

    public function Project_list($page=1,$search_input=''){
      $perpage=10;
      $page=($page-1)*$perpage;

      $search='';
      if($search_input!=''){
        $search="WHERE ( name LIKE '%$search_input%' ' )";  
      }

      $sql = "SELECT * FROM Projects $search ORDER BY id LIMIT $page,$perpage";

      $query= $this->conn->query($sql);
      $Project=array();
      if ($query->num_rows > 0) {
        while ($row = $query->fetch_assoc()) {
          //$Project["Project_data"][]= json_encode($this->to_utf8($row), JSON_UNESCAPED_UNICODE);
          $Project["Project_data"][]= json_encode($row);
        }
      }else{
        echo("Что-то пошло не так ");
      }

      $count_sql = "SELECT COUNT(*) FROM Projects $search";
      $query=  $this->conn->query($count_sql);
      $total = mysqli_fetch_row($query);
      $Project['total'][]= $total;


      return $Project;  
   }
    
    public function create_Project_info($post_data=array()){

      $name='';
      if(isset($post_data->name))
        $name = $post_data->name;
      
      $descrip='';
      if(isset($post_data->description))
        $descrip = $post_data->description;
      
      $price='';
      if(isset($post_data->price))
        $price = $post_data->price;
      
      $area='';
      if(isset($post_data->area))
        $area = $post_data->area;
           
      $material='';
      if(isset($post_data->material))
        $material = $post_data->material;
      
      $outbuilding='';
      if(isset($post_data->outbuilding))
        $outbuilding = $post_data->outbuilding;
       
      $size='';
      if(isset($post_data->size))
        $size = $post_data->size;
      
      $floor='';
      if(isset($post_data->floor))
        $floor = $post_data->floor;

      $mansarda='';
      if(isset($post_data->mansarda))
        $mansarda = $post_data->mansarda;

      $sql="INSERT INTO Projects (name,description,price,area,material,outbuilding,size,floor,mansarda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
      $stmt = $this->conn->prepare($sql);
      if(!$stmt ){ 
          die( "SQL Error: {$this->conn->errno} - {$this->conn->error}" );
      }
      $stmt->bind_Param("ssiisssss", $name, $descrip, $price, $area, $material, $outbuilding, $size, $floor, $mansarda);
      $stmt->execute();
      $res = $stmt->get_result();
      
      $sql="Select id from Projects where name='$name'";        
      $result= $this->conn->query($sql);
      $row = $result->fetch_assoc();

      return $row['id'];
    }
    
    public function view_Project_by_Project_id($id){
       if(isset($id)){
        $Project_id= mysqli_real_escape_string($this->conn,trim($id));
        
        $sql="Select * from Projects where id='$Project_id'";
          
        $result=  $this->conn->query($sql);

        $row = $result->fetch_assoc();

        return json_encode($row);
       }  
    }
    
    
    public function update_Project_info($post_data=array()){
      if( isset($post_data->id)){
        $id = $post_data->id;
            
        $name='';
        if(isset($post_data->name))
          $name = $post_data->name;
        
        $descrip='';
        if(isset($post_data->description))
          $descrip = $post_data->description;
        
        $price='';
        if(isset($post_data->price))
          $price = $post_data->price;
        
        $area='';
        if(isset($post_data->area))
          $area = $post_data->area;
             
        $material='';
        if(isset($post_data->material))
          $material = $post_data->material;
        
        $outbuilding='';
        if(isset($post_data->outbuilding))
          $outbuilding = $post_data->outbuilding;
         
        $size='';
        if(isset($post_data->size))
          $size = $post_data->size;
        
        $floor='';
        if(isset($post_data->floor))
          $floor = $post_data->floor;

        $mansarda='';
        if(isset($post_data->mansarda))
          $mansarda = $post_data->mansarda;

        $sql="UPDATE Projects SET name=?,description=?,price=?,area=?,material=?,outbuilding=?,size=?,floor=?,mansarda=? WHERE id = $id";
      
        $stmt = $this->conn->prepare($sql);
        if(!$stmt ){ 
            die( "SQL Error: {$this->conn->errno} - {$this->conn->error}" );
        }
        $stmt->bind_Param("ssiisssss", $name, $descrip, $price, $area, $material, $outbuilding, $size, $floor, $mansarda);
        $stmt->execute();
        $res = $stmt->get_result();
        
        unset($post_data->id); 
        if($res){
          return 'Succefully Updated Project Info';     
        }else{
          return 'An error occurred while inserting data';     
        }
      }   
    }
    
    public function delete_Project_info_by_id($id){
       if(isset($id)){
        $Project_id= mysqli_real_escape_string($this->conn,trim($id));

        $sql="DELETE FROM  Projects  WHERE id = $Project_id";
        $result= $this->conn->query($sql);

        $sql="ALTER TABLE Projects AUTO_INCREMENT = 1";
        $this->conn->query($sql);
        
        if($result){
          return 'Successfully Deleted Project Info';     
        }else{
          return 'An error occurred while inserting data';     
        }
              
      }
    }
    function __destruct() {
    mysqli_close($this->conn);  
    }
    
}

?>