<?php

include_once '../config/database.php';
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// Retrieve the values from $_POST
$username = $_POST['username'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$repassword = $_POST['repassword'] ?? '';
$address = $_POST['address'] ?? '';

// Check if all fields are filled
if (!empty($username) && !empty($phone) && !empty($password) && !empty($repassword) && !empty($address)) {
    // Set user property values
    $user->username = $username;
    
    // Remove non-numeric characters from phone number
    $user->phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Check if phone number has 11 digits
    if (strlen($user->phone) === 11) {
        $user->password = base64_encode($password);
        $user->repassword = base64_encode($repassword);
        $user->address = $address;

        // Check if password and repassword fields are equal
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
            "message" => "Phone number should have 11 digits!",
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