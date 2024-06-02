<?php

include_once '../config/database.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Establish database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Retrieve the ID of the account to be deleted
    $id = $_POST['id'];

    // Check if the account exists
    $checkSql = "SELECT * FROM craftsman WHERE id = $id";
    $result = $conn->query($checkSql);

    if ($result->rowCount() > 0) {
        // Delete the account
        $deleteSql = "DELETE FROM craftsman WHERE id = $id";

        if ($conn->exec($deleteSql) !== FALSE) {
            echo "Account deleted successfully";
        } else {
            echo "Error deleting account: " . $conn->errorInfo()[2];
        }
    } else {
        echo "No account found for the given ID.";
    }

    // Close database connection
    $conn = null;
}
?>