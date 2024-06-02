<?php
include_once '../config/database.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Establish database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Retrieve existing data from the "craftsman" table
    $id = $_POST['id'];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $username = $row['username'];
        $phone = $row['phone'];
        $password = $row['password'];
        $address = $row['address'];
        

        // Display the existing data
        echo "Existing Data";
        echo "Username: $username";
        echo "Phone: $phone";
        echo "Password: $password";
        echo "Address: $address";
        

        // Receive updated user data from the request
        $newUsername = $_POST['new_username'];
        $newPhone = $_POST['new_phone'];
        $newPassword = base64_encode($_POST['new_password']);
        $newAddress = $_POST['new_address'];
        

        // Update data in the "craftsman" table
        $updateSql = "UPDATE users SET username='$newUsername', phone='$newPhone', password='$newPassword', address='$newAddress' WHERE id=$id";

        if ($conn->exec($updateSql) !== FALSE) {
            echo "<br>Data updated successfully";
        } else {
            echo "<br>Error updating data: " . $conn->errorInfo()[2];
        }
    } else {
        echo "No data found for the given ID.";
    }

    // Close database connection
    $conn = null;
}
?>