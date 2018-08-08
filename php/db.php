<?php
class db{    
    public static function connect_db() {
        $servername = "";
        $dbname = "";
        $username = "";
        $password = "";
    

        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->set_charset("utf8");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }else{
            return $conn;  
        }

    }
}