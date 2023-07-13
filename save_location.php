<?php
// Retrieve the location data from the form submission
$address = $_POST['address'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];

// Connect to the MySQL database
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'ebarangaydatabase';
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL statement to save the location
$sql = "INSERT INTO testLoc (address, latitude, longitude) VALUES ('$address', '$lat', '$lng')";
if ($conn->query($sql) === TRUE) {
    echo "Location saved successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
