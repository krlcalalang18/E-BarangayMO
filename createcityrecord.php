<?php

session_start();

if (!isset($_SESSION['sessionAdminID'])){

    header("Location: session_error_page_admin.php");
}
$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cityName = $_POST['cityName'];
    $cityExpiry = $_POST['cityExpiry'];

    $stmt = $conn->prepare("INSERT INTO city (cityName, cityExpiry) VALUES (?, ?)");
    $stmt->bind_param("ss", $cityName, $cityExpiry);
    $stmt->execute();
    $stmt->close();

    echo "City record created successfully!";
}

$conn->close();
?>