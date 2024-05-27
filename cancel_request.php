<?php
include 'db.php';

$response = [];

if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    try {
        // Delete the request from the database
        $stmt = $conn->prepare("DELETE FROM requests WHERE request_id = :request_id");
        $stmt->bindParam(':request_id', $request_id);
        $stmt->execute();

        $response['message'] = "Request deleted successfully";
    } catch (PDOException $e) {
        $response['error'] = $e->getMessage();
    }
} else {
    $response['error'] = "Request ID is required";
}

header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
