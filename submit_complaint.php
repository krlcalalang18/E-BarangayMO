<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Get form data
    $citizenID = "1";
    $complaintType = $_POST["complaintType"];
    $complaintAddress = $_POST["complaintAddress"];
    $barangayID = $_POST["barangayID"];
    $complaintDetails = $_POST["complaintDetails"];
    $complaintDateAndTime = $_POST["complaintDateAndTime"];
    $complaintEvidence = $_FILES["complaintEvidence"]["tmp_name"];
    $priorityLevel = "Normal";
    $complaintStatus = "Pending";
    

    if (isset($_FILES["complaintEvidence"]) && $_FILES["complaintEvidence"]["error"] === UPLOAD_ERR_OK) {
        $file = $_FILES["complaintEvidence"];
        $fileName = $file["name"];
        $fileTmpPath = $file["tmp_name"];
        $fileSize = $file["size"];

        $fileContent = file_get_contents($fileTmpPath);

        $sql = "INSERT INTO complaint (citizenID, complaintType, complaintAddress, barangayID, complaintDetails, complaintEvidence, priorityLevel, complaintStatus, complaintDateAndTime)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssbsss", $citizenID, $complaintType, $complaintAddress, $barangayID, $complaintDetails, $fileContent, $priorityLevel, $complaintStatus, $complaintDateAndTime);
        //$fileContent is for complaintEvidence
        mysqli_stmt_send_long_data($stmt, 3, $fileContent);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Complaint updated successfully!";
        } else {
            echo "Error updating complaint.";
        }

        mysqli_stmt_close($stmt);

    } else {
        $sql = "UPDATE complaint SET complaintStatus = ?, remarks = ?, priorityLevel = ? WHERE complaintID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $complaintStatus, $remarks, $priorityLevel, $complaintID);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Complaint updated successfully.";
        } else {
            echo "Error updating complaint.";
        }

        mysqli_stmt_close($stmt);
}



    $conn->close();
}
?>
