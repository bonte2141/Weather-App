<?php
include 'db.php';

$responseArray = []; 

try {
    $stmt = $conn->prepare("SELECT * FROM real_time_weather");
    $stmt->execute();
    $responseArray = $stmt->fetchAll(PDO::FETCH_ASSOC); 
} catch(PDOException $e) {
    $responseArray = ['error' => $e->getMessage()];
}

echo json_encode($responseArray); 

$conn = null;
?>
