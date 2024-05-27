<?php
include 'db.php';

session_start();

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // This will be 'user', 'meteorologist', or 'admin'

    try {
        if ($role == 'user') {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        } elseif ($role == 'meteorologist') {
            $stmt = $conn->prepare("SELECT * FROM meteorologists WHERE email = ?");
        } else {
            $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        }
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $role;
            $response['message'] = "Login successful";
        } else {
            $response['error'] = "Invalid email or password";
        }
    } catch (PDOException $e) {
        $response['error'] = $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn = null;
?>
