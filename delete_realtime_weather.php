<?php
include 'db.php';

// Check if the request is a GET request
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM real_time_weather WHERE entry_id = :id"); // Use the correct primary key column name
        $stmt->bindParam(':id', $id);

        $stmt->execute();

        // Echo a response
        echo "Record deleted successfully";
    } catch(PDOException $e) {
        // Echo a response with error
        echo "Error: " . $e->getMessage();
    }
}

$conn = null;
?>
