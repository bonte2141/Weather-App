<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] == 'admin') {
    header("Location: main_admin.html");
} elseif ($_SESSION['role'] == 'meteorologist') {
    header("Location: main_mt.html");
} else {
    header("Location: index.html"); // Assuming you have a main page for regular users
}
exit();
?>
