<?php
$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$middleName = $_POST['middleName'];
$email = $_POST['email'];
$streetAddress = $_POST['streetAddress'];
$city = $_POST['city'];
$barangay = $_POST['barangay'];
$cellphoneNumber = $_POST['cellphoneNumber'];
$password = $_POST['password'];
$accountStatus = $_POST['accountStatus'];

$sql = "INSERT INTO user (firstName, lastName, middleName, emailAddress, streetAddress, city, barangay, cellphoneNumber, password, accountType, accountStatus)
        VALUES ('$firstName', '$lastName', '$middleName', '$email', '$streetAddress', '$city', '$barangay', '$cellphoneNumber', '$password', 'Administrator', '$accountStatus')";

if ($conn->query($sql) === TRUE) {
    $userID = $conn->insert_id;

    $adminSQL = "INSERT INTO administrator (adminID, userID)
                 VALUES (NULL, '$userID')";

    if ($conn->query($adminSQL) === TRUE) {
        echo "Administrator record inserted successfully.";
    } else {
        echo "Error inserting administrator record: " . $conn->error;
    }
} else {
    echo "Error inserting user record: " . $conn->error;
}

$conn->close();
?>
