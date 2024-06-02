<?php
include_once '../config/database.php';

// Check if the username is provided
if (isset($_POST['user_name'])) {
    // Retrieve the username
    $user_name = $_POST['user_name'];

    // Establish database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Prepare the SQL statement
    $sql = "SELECT * FROM `order` WHERE user_name = :user_name";
    $stmt = $conn->prepare($sql);

    // Bind the parameter
    $stmt->bindParam(':user_name', $user_name);

    // Execute the query
    if ($stmt->execute()) {
        // Fetch all rows as an associative array
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if there are any orders found
        if (!empty($orders)) {
            // Output the orders
            foreach ($orders as $order) {
                echo "Order ID: " . $order['order_id'] . "<br>";
                echo "Order Name: " . $order['order_name'] . "<br>";
                echo "Order Notes: " . $order['order_notes'] . "<br>";
                echo "Craftsman Name: " . $order['craftsman_name'] . "<br>";
                echo "User Name: " . $order['user_name'] . "<br>";
                echo "<br>";
            }
        } else {
            // No orders found
            echo "No orders found for user: " . $user_name;
        }
    } else {
        // Error executing the query
        echo "Error retrieving orders: " . $stmt->errorInfo()[2];
    }

    // Close database connection
    $conn = null;
} else {
    // No username provided
    echo "Please provide a username.";
}
?>