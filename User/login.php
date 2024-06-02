<?php
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
require_once '../vendor/autoload.php'; 

use Firebase\JWT\JWT;
// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare user object
$user = new User($db);

// check if all fields are filled
if(!empty($_POST['phone']) && !empty($_POST['password'])){
    // set phone and password property of user
    $user->phone = $_POST['phone'];
    $user->password = base64_encode($_POST['password']);  

    // read the details of user
    $stmt = $user->login();
    if($stmt->rowCount() > 0){
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $token = generateToken($row); // generate token

        // update token in the database
        if($user->updateToken($row['id'], $token)){
            // create array
            $user_arr = array(
                "status" => true,
                "message" => "Successfully Login!",
                "token" => $token
            );
        } else {
            $user_arr = array(
                "status" => false,
                "message" => "Failed to update token in the database"
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

// make it json format
echo json_encode($user_arr);

function generateToken($userData) {
    $secretKey = ""; // أدخل مفتاحك السري هنا

    $payload = array(
        "data" => $userData
    );

    $token = JWT::encode($payload, $secretKey, 'HS256');

    return $token;
}
?>
