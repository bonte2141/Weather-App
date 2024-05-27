<?php
include 'db.php';

$responseArray = [];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    try {
        $stmt = $conn->prepare("SELECT * FROM hazard_tracking WHERE hazard_name LIKE ? OR location LIKE ?");
        $stmt->execute(["%$searchQuery%", "%$searchQuery%"]);
        $responseArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $responseArray = ['error' => $e->getMessage()];
    }
}

echo json_encode($responseArray);

$conn = null;
?>
