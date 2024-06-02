<?php

include_once '../config/database.php';
include_once '../objects/craftsman.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$username = $_POST['username'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$repassword = $_POST['repassword'] ?? '';
$address = $_POST['address'] ?? '';
$craft = $_POST['craft'] ?? '';

if (!empty($username) && !empty($craft) && !empty($password) && !empty($repassword) && !empty($address) && !empty($phone)) {
    $user->username = $username;

    $phone = preg_replace('/[^0-9]/', '', $phone); 
    if (strlen($phone) !== 11) {
        $user_arr = array(
            "status" => false,
            "message" => "Invalid phone number format!",
        );
        print_r(json_encode($user_arr));
        exit;
    }

    $user->phone = $phone;
    $user->password = base64_encode($password);
    $user->repassword = base64_encode($repassword);
    $user->address = $address;
    $user->craft = $craft;

    if ($user->password === $user->repassword) {
        if ($user->isAlreadyExist()) {
            $user_arr = array(
                "status" => false,
                "message" => "Phone already exists!",
            );
        } else {
            try {
                if ($user->signup()) {
                    $user_arr = array(
                        "status" => true,
                        "message" => "Successfully Signup!",
                        "username" => $user->username,
                        "id" => $user->id,
                        "phone" => $user->phone,
                        "address" => $user->address,
                        "craft" => $user->craft,
                    );
                } else {
                    $user_arr = array(
                        "status" => false,
                        "message" => "Failed to signup!",
                    );
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $user_arr = array(
                        "status" => false,
                        "message" => "Username already exists!",
                    );
                } else {
                    $user_arr = array(
                        "status" => false,
                        "message" => "Database error: " . $e->getMessage(),
                    );
                }
            }
        }

        print_r(json_encode($user_arr));
    } else {
        $user_arr = array(
            "status" => false,
            "message" => "Passwords do not match!",
        );
        print_r(json_encode($user_arr));
    }
} else {
    $user_arr = array(
        "status" => false,
        "message" => "Please fill in all fields!",
    );
    print_r(json_encode($user_arr));
}
?>