<?php
// GALING SA PHONE
$complaintType = $_POST['complaintType'];
$complaintAddress = $_POST['complaintAddress'];
$complaintDetails = $_POST['complaintDetails'];

// LOGIN SESSION
//GALING SA PHONE THROUGH SESSION
$citizenID = 1;
$barangayID = 1;

//DEFAULT
$priorityLevel = "Normal";
$complaintStatus = "Pending";

// Create a connection to the database
$host = "localhost";
$dbName = "ebarangaydatabase";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO complaint (complaintType, complaintAddress, complaintDetails, complaintDateAndTime, citizenID, priorityLevel, complaintStatus, barangayID) VALUES (:complaintType, :complaintAddress, :complaintDetails, NOW(), :citizenID, :priorityLevel, :complaintStatus, :barangayID)");

    // Bind the parameters
    $stmt->bindParam(':complaintType', $complaintType);
    $stmt->bindParam(':complaintAddress', $complaintAddress);
    $stmt->bindParam(':complaintDetails', $complaintDetails);
    $stmt->bindParam(':citizenID', $citizenID);
    $stmt->bindParam(':priorityLevel', $priorityLevel);
    $stmt->bindParam(':complaintStatus', $complaintStatus);
    $stmt->bindParam(':barangayID', $barangayID);

    // Execute the query
    $stmt->execute();

    echo "Complaint record created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>
