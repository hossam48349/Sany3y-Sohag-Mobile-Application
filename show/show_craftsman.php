<?php
include_once '../config/database.php';

$database = new Database();
$connection = $database->getConnection();

if ($connection) {
    $craft = isset($_POST['craft']) ? $_POST['craft'] : die();
    $address = isset($_POST['address']) ? $_POST['address'] : die();
    
    $query = "SELECT * FROM craftsman WHERE craft = :craft AND address = :address";
    
    $statement = $connection->prepare($query);
    
    $statement->bindParam(':craft', $craft);
    $statement->bindParam(':address', $address);

    if ($statement->execute()) {
        if ($statement->rowCount() > 0) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo '
                    Name: '.$row['username'].'
                    Phone: '.$row['phone'].'
                    Craft: '.$row['craft'].'
                    Address: '.$row['address'].'
                    ';
            }
        } else {
            echo 'No users found with the craft value "' . $craft . '" and address "' . $address . '".';
        }
    } else {
        echo 'Error executing the query: '.$statement->errorInfo()[2];
    }
} else {
    echo 'Database connection failed.';
}
?>