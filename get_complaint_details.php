<?php
// Check if complaintID is provided
if (isset($_POST['complaintID'])) {
    $complaintID = $_POST['complaintID'];

    // Fetch complaint details from the database
    // Replace the following code with your database query logic

    // Example using PDO
    $dsn = 'mysql:host=localhost;dbname=ebarangaydatabase';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare('SELECT * FROM complaint WHERE complaintID = :complaintID');
        $stmt->bindParam(':complaintID', $complaintID);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $complaintDetails = $row['complaintDetails'];
            $remarks = $row['remarks'];
            $remarksEvidence = $row['remarksEvidence'];

            ?>
            
            <div class="form-group">
                <label for="complaintDetails">Complaint Details:</label>
                <textarea class="form-control" id="complaintDetails" readonly><?php echo $complaintDetails; ?></textarea>
            </div>
            <div class="form-group">
                <label for="remarks">Remarks:</label>
                <input type="text" class="form-control" id="remarks" value="<?php echo $remarks; ?>">
            </div>
            <div class="form-group">
                <label for="remarksEvidence">Remarks Evidence:</label>
                <?php
                // Display the remarks evidence image if available
                if (!empty($remarksEvidence)) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($remarksEvidence) . '" width="100" height="100">';
                }
                ?>
                <input type="file" class="form-control-file" id="remarksEvidence">
            </div>

            <?php
        } else {
            echo "No complaint found.";
        }

        $conn = null;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
