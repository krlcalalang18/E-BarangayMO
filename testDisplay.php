<!DOCTYPE html>
<html>
<head>
    <title>Complaint Records</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Complaint Records</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Complainant Name</th>
                    <th>Complainant Cellphone No</th>
                    <th>Date and Time</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Barangay</th>
                    <th>Details</th>
                    <th>Type</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Evidence</th>
                    <th>Remarks</th>
                    <th>Remarks Evidence</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Retrieve complaint records from the database
                $sql = "SELECT DISTINCT c.complaintID, CONCAT(u.firstName, ' ', u.lastName) AS ComplainantName, u.cellphoneNumber AS ComplainantCellphoneNo, c.complaintDateAndTime, c.complaintAddress, ct.cityName AS City, bs.barangayName AS Barangay, c.complaintDetails, c.complaintType, c.priorityLevel, c.complaintStatus, c.complaintEvidence, c.remarks, c.remarksEvidence
                        FROM complaint c
                        INNER JOIN user u ON c.citizenID = u.userID
                        INNER JOIN barangay_station bs ON c.barangayID = bs.barangayID
                        INNER JOIN city ct ON c.barangayID = bs.cityID
                        WHERE complaintStatus = 'Pending'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $complaintID = $row["complaintID"];
                        $complainantName = $row["ComplainantName"];
                        $complainantCellphone = $row["ComplainantCellphoneNo"];
                        $complaintDateAndTime = $row["complaintDateAndTime"];
                        $complaintAddress = $row["complaintAddress"];
                        $city = $row["City"];
                        $barangay = $row["Barangay"];
                        $complaintDetails = $row["complaintDetails"];
                        $complaintType = $row["complaintType"];
                        $priorityLevel = $row["priorityLevel"];
                        $complaintStatus = $row["complaintStatus"];
                        $complaintEvidence = base64_encode($row["complaintEvidence"]);
                        $remarks = $row["remarks"];
                        $remarksEvidence = base64_encode($row["remarksEvidence"]);

                        echo "<tr>
                                <td>$complaintID</td>
                                <td>$complainantName</td>
                                <td>$complainantCellphone</td>
                                <td>$complaintDateAndTime</td>
                                <td>$complaintAddress</td>
                                <td>$city</td>
                                <td>$barangay</td>
                                <td>$complaintDetails</td>
                                <td>$complaintType</td>
                                <td>$priorityLevel</td>
                                <td>$complaintStatus</td>
                                <td><img src='data:image/jpeg;base64,$complaintEvidence' style='width: 100px;'></td>
                                <td>$remarks</td>
                                <td><img src='data:image/jpeg;base64,$remarksEvidence' style='width: 100px;'></td>
                                <td>
                                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$complaintID'>
                                        View
                                    </button>
                                </td>
                            </tr>";

                        // Modal for viewing and updating complaint details
                        echo "<div class='modal fade' id='myModal$complaintID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='myModalLabel'>Complaint Details</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                        
                                            <form method='POST' action='update_complaint.php'>
                                                <input type='hidden' name='complaintID' value='$complaintID'>
                                                <div class='form-group'>
                                                    <label for='status'>Status</label>
                                                    <select class='form-control' name='status'>
                                                        <option value='Pending' " . ($complaintStatus == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                        <option value='Processing' " . ($complaintStatus == 'Processing' ? 'selected' : '') . ">Processing</option>
                                                        <option value='Complete' " . ($complaintStatus == 'Complete' ? 'selected' : '') . ">Complete</option>
                                                    </select>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='remarks'>Remarks</label>
                                                    <input type='text' class='form-control' name='remarks' value='$remarks'>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='remarksEvidence'>Remarks Evidence</label>
                                                    <input type='file' class='form-control' name='remarksEvidence'>
                                                </div>
                                                <button type='submit' class='btn btn-primary'>Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                    }
                } else {
                    echo "<tr><td colspan='14'>No records found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
