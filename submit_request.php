<?php
include 'db.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $page = $_POST['page'];
    $meteorologist_name = "John Doe"; // Replace with actual meteorologist's name

    if ($action === 'add') {
        $data = [
            'country' => $_POST['country'],
            'city' => $_POST['city'],
            'temp' => $_POST['temp'],
            'condition' => $_POST['condition'],
            'flag_image' => $_FILES['flag_image']['name']
        ];
    } elseif ($action === 'edit') {
        $data = [
            'entry_id' => $_POST['entry_id'],
            'country' => $_POST['country'],
            'city' => $_POST['city'],
            'temp' => $_POST['temp'],
            'condition' => $_POST['condition'],
            'flag_image' => $_FILES['flag_image']['name']
        ];
    } elseif ($action === 'delete') {
        $data = ['id' => $_POST['id']];
    }

    try {
        $stmt = $conn->prepare("INSERT INTO requests (meteorologist_name, page_name, action_type, data, status) VALUES (:meteorologist_name, :page_name, :action_type, :data, 'pending')");
        $stmt->bindParam(':meteorologist_name', $meteorologist_name);
        $stmt->bindParam(':page_name', $page);
        $stmt->bindParam(':action_type', $action);
        
        $jsonData = json_encode($data);
        $stmt->bindParam(':data', $jsonData);

        $stmt->execute();
        $response['message'] = "Request submitted successfully";
    } catch (PDOException $e) {
        $response['error'] = $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
