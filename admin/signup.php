<?php

// get database connection
include_once '../config/database.php';

// instantiate user object
include_once '../objects/admin.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// set user property values
$user->username =  $_POST['username'];
$user->email = $_POST['email'];
$user->password = base64_encode($_POST['password']);
$user->type =  $_POST['type'];



// create the user
if($user->signup()){
    $user_arr=array(
        "status" => true,
        "message" => "Successfully Signup!",
        "id" => $user->id,
        "username" =>$user->username,
        "email" => $user->email,
        "type"=>$user->type

    );
}
else{
    $user_arr=array(
        "status" => false,
        "message" => "email already exists!"
    );
}
print_r(json_encode($user_arr));
?>