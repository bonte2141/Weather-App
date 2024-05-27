<?php
include 'db.php';

$response = [];

try {
    $stmt = $conn->prepare("SELECT request_id, page_name, action_type, data, status FROM requests WHERE meteorologist_name = 'John Doe'");
    $stmt->execute();
    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $response['error'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
