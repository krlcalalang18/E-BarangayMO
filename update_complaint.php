<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the complaint ID and other form inputs
    $complaintID = $_POST["complaintID"];
    $status = $_POST["status"];
    $remarks = $_POST["remarks"];
    $priority = $_POST["priority"];

    // Perform database update
    $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update complaint status and remarks
    $sql = "UPDATE complaint SET complaintStatus = '$status', remarks = '$remarks', priorityLevel = '$priority' WHERE complaintID = $complaintID";
    if ($conn->query($sql) === TRUE) {
        echo "Complaint updated successfully";
        echo "<a href='admin.php'> Go Back </a>";
    } else {
        echo "Error updating complaint: " . $conn->error;
    }

    $conn->close();
}
?>