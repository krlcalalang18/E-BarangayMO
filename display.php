<!DOCTYPE html>
<html>
<head>
    <title>Complaint Records</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    
</head>
<body>
    <div class="container">
        <h1>Complaint Records</h1>

        <?php
        $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT c.complaintID, CONCAT(u.firstName, ' ', u.lastName) AS ComplainantName, u.cellphoneNumber AS ComplainantCellphoneNo, c.complaintDateAndTime, c.complaintAddress, ct.cityName AS City, bs.barangayName AS Barangay, c.complaintDetails, c.complaintType, c.priorityLevel, c.complaintStatus, c.complaintEvidence, c.remarks, c.remarksEvidence
            FROM complaint c
            INNER JOIN user u ON c.citizenID = u.userID
            INNER JOIN barangay_station bs ON c.barangayID = bs.barangayID
            INNER JOIN city ct ON c.barangayID = bs.cityID
            WHERE complaintStatus = 'Pending'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<table class="table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Complaint ID</th>';
            echo '<th>Complaintant Name</th>';
            echo '<th>Complaint Cellphone No.</th>';
            echo '<th>Complaint Date and Time</th>';
            echo '<th>Complaint Address</th>';
            echo '<th>City</th>';
            echo '<th>Barangay</th>';
            echo '<th>Complaint Details</th>';
            echo '<th>Complaint Type</th>';
            echo '<th>Priority</th>';
            echo '<th>Complaint Status</th>';
            echo '<th>Complaint Evidence</th>';
            echo '<th>Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo "<td>" . $row["complaintID"] . "</td>";
                echo "<td>" . $row["ComplainantName"] . "</td>";
                echo "<td>" . $row["ComplainantCellphoneNo"] . "</td>";
                echo "<td>" . $row["complaintDateAndTime"] . "</td>";
                echo "<td>" . $row["complaintAddress"] . "</td>";
                echo "<td>" . $row["City"] . "</td>";
                echo "<td>" . $row["Barangay"] . "</td>";
                echo "<td>" . $row["complaintDetails"] . "</td>";
                echo "<td>" . $row["complaintType"] . "</td>";
                echo "<td>" . $row["priorityLevel"] . "</td>";
                echo "<td>" . $row["complaintStatus"] . "</td>";
                echo '<td>';
                if ($row["complaintEvidence"] !== null) {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row["complaintEvidence"]) . '" width="100" height="100" alt="Complaint Evidence">';
                }
                echo '</td>';
                echo '<td><button type="button" class="btn btn-primary view-btn" data-toggle="modal" data-target="#viewModal" data-complaint="' . htmlspecialchars(json_encode($row)) . '">View</button></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No records found.</p>';
        }

        $conn->close();
        ?>

        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel">Complaint Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Complaint ID:</strong> <span id="complaintID"></span></p>
                        <p><strong>Complaint Type:</strong> <span id="complaintType"></span></p>
                        <p><strong>Complaint Address:</strong> <span id="complaintAddress"></span></p>
                        <p><strong>Complaint Details:</strong> <span id="complaintDetails"></span></p>
                        <p><strong>Date and Time:</strong> <span id="complaintDateAndTime"></span></p>
                        <p><strong>Priority Level:</strong> <span id="priorityLevel"></span></p>
                        <p><strong>Status:</strong> <span id="complaintStatus"></span></p>
                        <p><strong>Citizen Name:</strong> <span id="citizenName"></span></p>
                        <p><strong>Barangay ID:</strong> <span id="barangayID"></span></p>
                        <p><strong>Remarks:</strong> <span id="remarks"></span></p>
                        <p><strong>Remarks Evidence:</strong> <span id="remarksEvidence"></span></p>
                        <div id="complaintEvidenceContainer"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('.view-btn').click(function() {
                    var complaint = $(this).data('complaint');

                    // Display the complaint details in the modal
                    $('#complaintID').text(complaint.complaintID);
                    $('#complaintType').text(complaint.complaintType);
                    $('#complaintAddress').text(complaint.complaintAddress);
                    $('#complaintDetails').text(complaint.complaintDetails);
                    $('#complaintDateAndTime').text(complaint.complaintDateAndTime);
                    $('#priorityLevel').text(complaint.priorityLevel);
                    $('#complaintStatus').text(complaint.complaintStatus);
                    $('#citizenName').text(complaint.citizenName);
                    $('#barangayID').text(complaint.barangayID);
                    $('#remarks').text(complaint.remarks);
                    $('#remarksEvidence').text(complaint.remarksEvidence);

                    // Display the complaint evidence
                    if (complaint.complaintEvidence !== null) {
                        var evidenceHtml = '<h6>Complaint Evidence:</h6>';
                        evidenceHtml += '<img src="data:image/jpeg;base64,' + complaint.complaintEvidence + '" class="img-fluid" alt="Complaint Evidence">';
                        $('#complaintEvidenceContainer').html(evidenceHtml);
                    } else {
                        $('#complaintEvidenceContainer').html('');
                    }
                });
            });
        </script>
    </div>
</body>
</html>
