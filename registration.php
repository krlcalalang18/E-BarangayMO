<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "ebarangaydatabase";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitize($data)
{
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = sanitize($_POST['firstName']);
    $lastName = sanitize($_POST['lastName']);
    $middleName = sanitize($_POST['middleName']);
    $emailAddress = sanitize($_POST['emailAddress']);
    $streetAddress = sanitize($_POST['streetAddress']);
    $city = sanitize($_POST['city']);
    $barangay = sanitize($_POST['barangay']);
    $cellphoneNumber = sanitize($_POST['cellphoneNumber']);
    $password = sanitize($_POST['password']); 
    $accountType = sanitize($_POST['accountType']);
    $accountStatus = sanitize($_POST['accountStatus']);
    $idNumber = sanitize($_POST['idNumber']);
    $idType = sanitize($_POST['idType']);
    $idExpiry = sanitize($_POST['idExpiry']);
    $idBirthday = sanitize($_POST['idBirthday']);

    $idPicture = $_FILES['idPicture']['tmp_name'];
    $idPictureData = addslashes(file_get_contents($idPicture));
    $idPictureType = $_FILES['idPicture']['type'];

    $idSelfPhoto = $_FILES['idSelfPhoto']['tmp_name'];
    $idSelfPhotoData = addslashes(file_get_contents($idSelfPhoto));
    $idSelfPhotoType = $_FILES['idSelfPhoto']['type'];

    $userQuery = "INSERT INTO user (firstName, lastName, middleName, emailAddress, streetAddress, city, barangay, cellphoneNumber, password, accountType, accountStatus)
                VALUES ('$firstName', '$lastName', '$middleName', '$emailAddress', '$streetAddress', '$city', '$barangay', '$cellphoneNumber', '$password', '$accountType', '$accountStatus')";
    $userResult = $conn->query($userQuery);
    if (!$userResult) {
        die("Error inserting data into 'user' table: " . $conn->error);
    }

    $userID = $conn->insert_id;

    $citizenQuery = "INSERT INTO citizen (userID, idNumber, idType, idExpiry, idBirthday, idPicture, idSelfPhoto)
                    VALUES ('$userID', '$idNumber', '$idType', '$idExpiry', '$idBirthday', '$idPictureData', '$idSelfPhotoData')";
    $citizenResult = $conn->query($citizenQuery);
    if (!$citizenResult) {
        die("Error inserting data into 'citizen' table: " . $conn->error);
    }

    header("Location: citizen_login_page.html");
    exit();
}

$conn->close();
?>
