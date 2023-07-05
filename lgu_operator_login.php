<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cellphoneNumber = $_POST['cellphoneNumber'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE cellphoneNumber = '$cellphoneNumber' AND password = '$password' AND accountType = 'LGU Operator'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['LGUOperatorID'] = $row['userID'];
    header("Location: display_citizen.php");
} else {
    echo "Invalid cellphone number or password.";
    echo "<a href='lgu_operator_login_page.php'>Back to login</a>";
}

$conn->close();
?>