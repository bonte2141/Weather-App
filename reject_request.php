<?php
include 'db.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'];

    try {
        $stmt = $conn->prepare("UPDATE requests SET status = 'rejected' WHERE request_id = :request_id");
        $stmt->bindParam(':request_id', $request_id);
        $stmt->execute();

        $response['message'] = "Request rejected successfully";
    } catch (PDOException $e) {
        $response['error'] = $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
