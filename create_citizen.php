<?php
// Retrieve the form data
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$middleName = $_POST['middleName'];
$emailAddress = $_POST['emailAddress'];
$streetAddress = $_POST['streetAddress'];
$city = $_POST['city'];
$barangay = $_POST['barangay'];
$cellphoneNumber = $_POST['cellphoneNumber'];
$password = $_POST['password'];
$accountType = $_POST['accountType'];
$accountStatus = $_POST['accountStatus'];

// Create a connection to the database
$host = "localhost"; // Replace with your host
$dbName = "ebarangaydatabase"; // Replace with your database name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insert into the "user" table
    $userStmt = $conn->prepare("INSERT INTO user (firstName, lastName, middleName, emailAddress, streetAddress, city, barangay, cellphoneNumber, password, accountType, accountStatus) VALUES (:firstName, :lastName, :middleName, :emailAddress, :streetAddress, :city, :barangay, :cellphoneNumber, :password, :accountType, :accountStatus)");
    
    $userStmt->bindParam(':firstName', $firstName);
    $userStmt->bindParam(':lastName', $lastName);
    $userStmt->bindParam(':middleName', $middleName);
    $userStmt->bindParam(':emailAddress', $emailAddress);
    $userStmt->bindParam(':streetAddress', $streetAddress);
    $userStmt->bindParam(':city', $city);
    $userStmt->bindParam(':barangay', $barangay);
    $userStmt->bindParam(':cellphoneNumber', $cellphoneNumber);
    $userStmt->bindParam(':password', $password);
    $userStmt->bindParam(':accountType', $accountType);
    $userStmt->bindParam(':accountStatus', $accountStatus);

    // Execute the user table insertion
    $userStmt->execute();

    // Get the last inserted user ID
    $userID = $conn->lastInsertId();

    // Insert into the "citizen" table
    $citizenStmt = $conn->prepare("INSERT INTO citizen (citizenID, userID) VALUES (DEFAULT, :userID)");
    $citizenStmt->bindParam(':userID', $userID);

    // Execute the citizen table insertion
    $citizenStmt->execute();

    echo "Citizen record created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
