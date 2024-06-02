<?php
include_once '../config/database.php';




// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $order_name = $_POST['order_name'];
    $order_notes = $_POST['order_notes'];
    $craftsman_name =$_POST['craftsman_name'];
    $user_name = $_POST['user_name'];

    // Establish database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Prepare the SQL statement
    $sql = "INSERT INTO `order` (order_name, order_notes, craftsman_name, user_name) VALUES (:order_name, :order_notes, :craftsman_name, :user_name)";
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':order_name', $order_name);
    $stmt->bindParam(':order_notes', $order_notes);
    $stmt->bindParam(':craftsman_name', $craftsman_name);
    $stmt->bindParam(':user_name', $user_name);

    // Execute the query
    if ($stmt->execute()) {
        echo "Data added successfully";
    } else {
        echo "Error adding data: " . $stmt->errorInfo()[2];
    }

    // Close database connection
    $conn = null;
}
?>