<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    try {
        $stmt = $conn->prepare("SELECT * FROM real_time_weather WHERE country LIKE ?");
        $stmt->execute(["%$searchQuery%"]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
     
        echo json_encode($results);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}

$conn = null;

?>
