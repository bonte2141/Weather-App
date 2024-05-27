<?php
include 'db.php';

$responseArray = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hazard_name = $_POST['hazard_name'];
    $status = $_POST['status'];
    $location = $_POST['location'];

    if (isset($_FILES['hazard_icon']) && $_FILES['hazard_icon']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['hazard_icon']['tmp_name'];
        $fileName = $_FILES['hazard_icon']['name'];
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            $stmt = $conn->prepare("INSERT INTO hazard_tracking (hazard_name, status, location, hazard_icon) VALUES (:hazard_name, :status, :location, :hazard_icon)");
            $stmt->bindParam(':hazard_name', $hazard_name);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':hazard_icon', $dest_path);
            if ($stmt->execute()) {
                $responseArray = ['message' => 'Hazard data added successfully.'];
            } else {
                $responseArray = ['error' => 'Failed to add hazard data.'];
            }
        } else {
            $responseArray = ['error' => 'Error uploading the hazard icon.'];
        }
    } else {
        $responseArray = ['error' => 'No file uploaded or upload error.'];
    }
} else {
    $responseArray = ['error' => 'Invalid request method.'];
}

echo json_encode($responseArray);
?>
