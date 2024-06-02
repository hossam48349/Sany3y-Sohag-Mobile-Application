
<?php
include_once '../config/database.php';

$database = new Database();
$connection = $database->getConnection();


if ($connection) {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        $query = "SELECT * FROM users WHERE token = :token";
        
        $statement = $connection->prepare($query);
        
        $statement->bindParam(':token', $token);
    
        if ($statement->execute()) {
            if ($statement->rowCount() > 0) {
                $user = $statement->fetch(PDO::FETCH_ASSOC);
                
                header('Content-Type: application/json');
                echo json_encode($user);
            } else {
                $response = array('error' => "No user found with this '$token'");
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else {
            $response = array('error' => 'Error executing the query: '.$statement->errorInfo()[2]);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    } else {
        $response = array('error' => 'token number is missing');
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    $response = array('error' => 'Database connection failed');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>