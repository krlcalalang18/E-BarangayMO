<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cellphoneNumber = $_POST['cellphoneNumber'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE cellphoneNumber = '$cellphoneNumber' AND password = '$password' AND accountType = 'Administrator'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['adminID'] = $row['userID'];
    header("Location: display_operator.php");
} else {
    echo "Invalid cellphone number or password.";
}

$conn->close();
?>