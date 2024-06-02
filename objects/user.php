<?php

class User{
    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $phone;
    public $username;
    public $password;
    public $repassword;
    public $address;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // تعريف الدالة لاسترجاع بيانات المستخدم بواسطة الـ ID
    public function getUserById() {
        // query to get user data by ID
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind ID of user to be retrieved
        $stmt->bindParam(1, $this->id);
        
        // execute query
        $stmt->execute();
        
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            // إذا وجدت صفًا، قم بإعادة تشكيله ككائن stdClass
            $user = new stdClass();
            $user->id = $row['id'];
            $user->username = $row['username'];
            $user->phone = $row['phone'];
            $user->password = $row['password'];
            $user->address = $row['address'];
            return $user;
        } else {
            // إذا لم يتم العثور على أي بيانات، قم بإرجاع قيمة null
            return null;
        }
    }

    // تعريف الدالة لتحديث الـ token
    public function updateToken($id, $token){
        // update query
        $query = "UPDATE " . $this->table_name . "
                    SET token = :token
                    WHERE id = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // sanitize
        $id = htmlspecialchars(strip_tags($id));
        $token = htmlspecialchars(strip_tags($token));

        // bind new values
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':id', $id);

        // execute the query
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    // تعريف الدالة لتسجيل المستخدم
    public function signup(){
        if($this->isAlreadyExist()){
            return false;
        }
        // query to insert record of new user signup
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                username=:username, phone=:phone, password=:password, repassword=:repassword, address=:address";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->repassword=htmlspecialchars(strip_tags($this->repassword));
        $this->address=htmlspecialchars(strip_tags($this->address));

        // bind values
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":repassword", $this->repassword);
        $stmt->bindParam(":address", $this->address);

        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // تعريف الدالة لتسجيل دخول المستخدم
    public function login(){
        // select all query with user inputed phone and password
        $query = "SELECT id, username, phone, password, address
                FROM " . $this->table_name . " 
                WHERE phone=:phone AND password=:password";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->password=htmlspecialchars(strip_tags($this->password));
        // bind values
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":password", $this->password);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // تعريف الدالة للتحقق مما إذا كان المستخدم موجودًا بالفعل
    public function isAlreadyExist(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE phone=:phone";
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // sanitize
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        // bind value
        $stmt->bindParam(":phone", $this->phone);
        // execute query
        $stmt->execute();
        // check if phone exists
        if($stmt->rowCount() > 0){
            return true;
        } else {
            return false;
        }
    }
} // تغليق الكلاس

?>
