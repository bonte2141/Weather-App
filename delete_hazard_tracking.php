<?php
include 'db.php';

$responseArray = [];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conn->prepare("DELETE FROM hazard_tracking WHERE hazard_id = :id");
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            $responseArray = ['message' => 'Hazard data deleted successfully.'];
        } else {
            $responseArray = ['error' => 'Failed to delete hazard data.'];
        }
    } catch(PDOException $e) {
        $responseArray = ['error' => $e->getMessage()];
    }
} else {
    $responseArray = ['error' => 'No ID provided.'];
}

echo json_encode($responseArray);
?>
