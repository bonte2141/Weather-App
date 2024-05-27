<?php
include 'db.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $role = $_POST['role']; // This will be 'user', 'meteorologist', or 'admin'

    try {
        if ($role == 'user') {
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, gender, phone, country, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        } elseif ($role == 'meteorologist') {
            $stmt = $conn->prepare("INSERT INTO meteorologists (first_name, last_name, email, password, gender, phone, country, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        } else {
            $stmt = $conn->prepare("INSERT INTO admins (first_name, last_name, email, password, gender, phone, country, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        }
        $stmt->execute([$first_name, $last_name, $email, $password, $gender, $phone, $country, $address]);

        $response['message'] = "Registration successful";
    } catch (PDOException $e) {
        $response['error'] = $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
