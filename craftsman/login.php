<?php
include_once '../config/database.php';
include_once '../objects/craftsman.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if(!empty($_POST['phone']) && !empty($_POST['password'])){
    $user->phone = $_POST['phone'];
    $user->password = base64_encode($_POST['password']);  

    $stmt = $user->login();
    if($stmt->rowCount() > 0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $token = bin2hex(random_bytes(32)); 

        if ($user->updateToken($row['id'], $token)) {
            $user_arr = array(
                "status" => true,
                "message" => "Login successful!",
                "token" => $token 
            );
        } else {
           
            $user_arr = array(
                "status" => false,
                "message" => "Failed to update token!",
            );
        }
    }
    else{
        $user_arr = array(
            "status" => false,
            "message" => "Invalid phone or Password!",
        );
    }
}
else{
    $user_arr = array(
        "status" => false,
        "message" => "Phone and Password fields are required!",
    );
}

echo json_encode($user_arr);

?>
