<?php
if (isset($_GET["cityID"])) {
    $cityID = $_GET["cityID"];

    // Database connection details
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "ebarangaydatabase";

    // Create a connection
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch barangay data based on the selected city
    $sql = "SELECT barangayID, barangayName FROM barangay_station WHERE cityID = ? ORDER BY barangayName ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cityID);
    $stmt->execute();
    $result = $stmt->get_result();
    $barangays = array();
    while ($row = $result->fetch_assoc()) {
        $barangays[] = $row;
    }

    // Return barangay data as JSON
    echo json_encode($barangays);

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
