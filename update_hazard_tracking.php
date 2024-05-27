<?php
include 'db.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hazard_id = $_POST['hazard_id'];
    $hazard_name = $_POST['hazard_name'];
    $status = $_POST['status'];
    $location = $_POST['location'];
    $hazard_icon = '';

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
    }

    if (isset($_FILES['hazard_icon']) && $_FILES['hazard_icon']['error'] == 0) {
        $targetFile = $targetDir . basename($_FILES["hazard_icon"]["name"]);
        if (move_uploaded_file($_FILES["hazard_icon"]["tmp_name"], $targetFile)) {
            $hazard_icon = $targetFile;
        } else {
            $response['error'] = "Error uploading the hazard icon.";
            echo json_encode($response);
            exit;
        }
    } else {
        // If no new image is uploaded, use the existing image
        $stmt = $conn->prepare("SELECT hazard_icon FROM hazard_tracking WHERE hazard_id = :hazard_id");
        $stmt->bindParam(':hazard_id', $hazard_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hazard_icon = $row['hazard_icon'];
    }

    try {
        $stmt = $conn->prepare("UPDATE hazard_tracking SET hazard_name = :hazard_name, status = :status, location = :location, hazard_icon = :hazard_icon WHERE hazard_id = :hazard_id");
        $stmt->bindParam(':hazard_name', $hazard_name);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':hazard_icon', $hazard_icon);
        $stmt->bindParam(':hazard_id', $hazard_id);

        $stmt->execute();
        $response['message'] = "Record updated successfully";
    } catch (PDOException $e) {
        $response['error'] = $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
