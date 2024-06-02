<?php
// Include database and object files
include_once '../config/database.php';
include_once '../objects/craftsman.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();
$connection = $database->getConnection();

// Prepare user object
$user = new User($db);

if ($connection) {
    // Check if POST request contains the required data
    if (isset($_POST['phone']) && isset($_POST['password']) && isset($_POST['repassword'])) {
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['repassword'];

        // Validate password confirmation
        if ($password !== $passwordConfirmation) {
            echo "كلمة المرور وتأكيدها غير متطابقي. يرجى المحاولة مرة أخرى.";
            exit;
        }

        // Validate phone number length
        if (strlen($phone) != 11) {
            echo "رقم الهاتف يجب أن يكون 11 خانة فقط.";
            exit;
        }

        // Check if the phone number exists in the database
        $sqlCheckPhone = "SELECT COUNT(*) FROM craftsman WHERE phone = '$phone'";
        $stmtCheckPhone = $connection->prepare($sqlCheckPhone);
        $stmtCheckPhone->execute();
        $numRows = $stmtCheckPhone->fetchColumn();

        if ($numRows === 0) {
            echo "رقم الهاتف غير مسجل في قاعدة البيانات.";
            exit;
        }

        // Hash the new password
        $hashedPassword = base64_encode($password);

        // Update the user's password in the database
        $sqlUpdatePassword = "UPDATE craftsman SET password = '$hashedPassword' WHERE phone = '$phone'";
        $stmtUpdatePassword = $connection->prepare($sqlUpdatePassword);

        if ($stmtUpdatePassword->execute()) {
            echo "تم تحديث كلمة المرور بنجاح.";
        } else {
            echo "حدث خطأ أثناء تحديث كلمة المرور.";
        }
    } else {
        echo "Missing required data in the POST request.";
    }
} else {
    echo "Failed to connect to the database.";
}