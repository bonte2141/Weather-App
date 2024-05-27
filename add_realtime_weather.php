<?php
include 'db.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    }

    try {
        $stmt = $conn->prepare("INSERT INTO real_time_weather (country, city, temp, `condition`, flag_image) VALUES (:country, :city, :temp, :condition, :flag_image)");
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':temp', $temp);
        $stmt->bindParam(':condition', $condition);
        $stmt->bindParam(':flag_image', $flagImage);

        $stmt->execute();
        $response['message'] = "New record created successfully";
    } catch(PDOException $e) {
        $response['error'] = $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
