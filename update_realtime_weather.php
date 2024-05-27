<?php
include 'db.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entry_id = $_POST['entry_id'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $temp = $_POST['temp'];
    $condition = $_POST['condition'];
    $flagImage = '';

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Create the directory if it doesn't exist
    }

    if (isset($_FILES['flag_image']) && $_FILES['flag_image']['error'] == 0) {
        $targetFile = $targetDir . basename($_FILES["flag_image"]["name"]);
        if (move_uploaded_file($_FILES["flag_image"]["tmp_name"], $targetFile)) {
            $flagImage = $targetFile;
        } else {
            $response['error'] = "Error uploading the flag image.";
            echo json_encode($response);
            exit;
        }
    } else {
        // If no new image is uploaded, use the existing image
        $stmt = $conn->prepare("SELECT flag_image FROM real_time_weather WHERE entry_id = :entry_id");
        $stmt->bindParam(':entry_id', $entry_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $flagImage = $row['flag_image'];
    }

    try {
        $stmt = $conn->prepare("UPDATE real_time_weather SET country = :country, city = :city, temp = :temp, `condition` = :condition, flag_image = :flag_image WHERE entry_id = :entry_id");
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':temp', $temp);
        $stmt->bindParam(':condition', $condition);
        $stmt->bindParam(':flag_image', $flagImage);
        $stmt->bindParam(':entry_id', $entry_id);

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
