<?php
// Replace this with your database connection code
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ebarangaydatabase";

// Create a new PDO instance
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch complaint records from the database
$stmt = $conn->prepare("SELECT complaintID, complaintType, complaintAddress, complaintDetails, complaintDateAndTime, citizenID, priorityLevel, complaintStatus, barangayID FROM complaint");
$stmt->execute();
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaints</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    </style>
</head>
<body>
    <div class="container">
        <h1>Complaints</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Complaint Type</th>
                    <th>Complaint Address</th>
                    <th>Complaint Details</th>
                    <th>Date and Time</th>
                    <th>Citizen ID</th>
                    <th>Priority Level</th>
                    <th>Status</th>
                    <th>Barangay ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $complaint) { ?>
                <tr>
                    <td><?php echo $complaint['complaintID']; ?></td>
                    <td><?php echo $complaint['complaintType']; ?></td>
                    <td><?php echo $complaint['complaintAddress']; ?></td>
                    <td><?php echo $complaint['complaintDetails']; ?></td>
                    <td><?php echo $complaint['complaintDateAndTime']; ?></td>
                    <td><?php echo $complaint['citizenID']; ?></td>
                    <td><?php echo $complaint['priorityLevel']; ?></td>
                    <td><?php echo $complaint['complaintStatus']; ?></td>
                    <td><?php echo $complaint['barangayID']; ?></td>
                    <td>
                        <button type="button" class="btn btn-primary view-btn" data-toggle="modal" data-target="#viewModal" data-complaint-id="<?php echo $complaint['complaintID']; ?>">View</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
                    <p><strong>Citizen ID:</strong> <span id="citizenID"></span></p>
                    <p><strong>Priority Level:</strong> <span id="priorityLevel"></span></p>
                    <p><strong>Status:</strong> <span id="complaintStatus"></span></p>
                    <p><strong>Barangay ID:</strong> <span id="barangayID"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.view-btn').click(function() {
                var complaintID = $(this).data('complaint-id');
                var complaint = <?php echo json_encode($complaints); ?>;

                // Find the complaint object with the matching ID
                var selectedComplaint = complaint.find(function(complaint) {
                    return complaint.complaintID == complaintID;
                });

                // Display the complaint details in the modal
                $('#complaintID').text(selectedComplaint.complaintID);
                $('#complaintType').text(selectedComplaint.complaintType);
                $('#complaintAddress').text(selectedComplaint.complaintAddress);
                $('#complaintDetails').text(selectedComplaint.complaintDetails);
                $('#complaintDateAndTime').text(selectedComplaint.complaintDateAndTime);
                $('#citizenID').text(selectedComplaint.citizenID);
                $('#priorityLevel').text(selectedComplaint.priorityLevel);
                $('#complaintStatus').text(selectedComplaint.complaintStatus);
                $('#barangayID').text(selectedComplaint.barangayID);

                // Show the modal
                $('#viewModal').modal('show');
            });
        });
    </script>
</body>
</html>
