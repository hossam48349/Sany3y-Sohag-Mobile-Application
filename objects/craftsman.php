<?php

class User{
    private $conn;
    private $table_name = "craftsman";

    public $id;
    public $phone;
    public $username;
    public $password;
    public $repassword;
    public $address;
    public $craft;


    public function __construct($db){
        $this->conn = $db;
    }


    public function updateToken($user_id, $token){
        $query = "UPDATE " . $this->table_name . " SET token = :token WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":user_id", $user_id);
        return $stmt->execute();
    }

    public function signup(){
        if($this->isAlreadyExist()){
            return false;
        }
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                username=:username, phone=:phone, password=:password, repassword=:repassword, address=:address,craft=:craft";

      
        $stmt = $this->conn->prepare($query);

      
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->repassword=htmlspecialchars(strip_tags($this->repassword));
        $this->address=htmlspecialchars(strip_tags($this->address));
        $this->craft=htmlspecialchars(strip_tags($this->craft));


       
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":repassword", $this->repassword);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":craft", $this->craft);


       
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

   
    public function login(){
       
        $query = "SELECT id, username, phone, password, address,craft
                FROM " .$this->table_name . " 
                WHERE phone=:phone AND password=:password";
        $stmt = $this->conn->prepare($query);
       
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->password=htmlspecialchars(strip_tags($this->password));
       
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":password", $this->password);
       
        $stmt->execute();
        return $stmt;
    }

    
    public function isAlreadyExist(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE phone=:phone";
        
        $stmt = $this->conn->prepare($query);
       
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        
        $stmt->bindParam(":phone", $this->phone);
       
        $stmt->execute();
       
        if($stmt->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }
}
?>
