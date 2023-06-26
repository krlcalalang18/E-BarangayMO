<!DOCTYPE html>
<html>
<head>
    <title>Barangay Operator - Pending Complaints</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #007BFF, #004A8F);
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            background-color: #f1f1f1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #fff;
        }

        .tabs {
            margin-bottom: 20px;
        }

        .tab {
            display: block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .tab.active {
            background-color: #004A8F;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .btn-view {
            padding: 6px 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .btn-archive {
            padding: 6px 10px;
            background-color: #FF0000;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        /* SEARCH BAR */
        .search-container {
            text-align: right;
            margin-bottom: 10px;
        }

        .search-input {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-button {
            padding: 6px 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 5px;
        }

        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ccc;
            margin-bottom: 10px;
        }

        .profile-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .profile-title {
            color: #777;
        }

    </style>
</head>
<body>
    <?php

    $conn = new mysqli('localhost','root','','ebarangaydatabase');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // DELETE BUTTON
    if (isset($_POST["delete"])) {
        $complaintId = $_POST["complaint_id"];

        $stmt = $conn->prepare("DELETE FROM pendingComplaints WHERE complaintID = ?");
        $stmt->bind_param("i", $complaintId);
        $stmt->execute();

        $stmt->close();
    }

    // UPDATE STATUS
    if (isset($_POST["update_status"])) {
        $complaintId = $_POST["complaint_id"];
        $complaintStatus = $_POST["complaint_status"];

        $stmt = $conn->prepare("UPDATE complaint SET ComplaintStatus = ? WHERE complaintID = ?");
        $stmt->bind_param("si", $complaintStatus, $complaintId);
        $stmt->execute();

        $stmt->close();
    }

    $btnview = 'btn-view';
    $btnarchive = 'btn-archive';

    ?>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile">
                <div class="profile-picture"></div>
                <div class="profile-name">Juan Dela Cruz</div>
                <div class="profile-title">Barangay Operator</div>
            </div>
            <div class="tabs">
                <a href="brgyAdminProfile.html"><div class="tab">Profile</div></a>
                <a href="admin.php"><div class="tab active">Pending Complaints</div></a>
                <a href="adminProcessing.php"><div class="tab">Processing Complaints</div></a>
                <a href=""><div class="tab">Completed Complaints</div></a>
            </div>

        </div>
        <div class="content">
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search...">
                <button class="search-button">Search</button>
            </div>
            <h1>Pending Complaints</h1>

            <?php 
            
            $sql = "SELECT c.complaintID, CONCAT(u.firstName, ' ', u.lastName) AS ComplainantName, u.cellphoneNumber AS ComplainantCellphoneNo, c.complaintDateAndTime, c.complaintAddress, ct.cityName AS City, bs.barangayName AS Barangay, c.complaintDetails, c.complaintType, c.priorityLevel, c.complaintStatus, c.complaintEvidence, c.remarks, c.remarksEvidence
            FROM complaint c
            INNER JOIN user u ON c.citizenID = u.userID
            INNER JOIN barangay_station bs ON c.barangayID = bs.barangayID
            INNER JOIN city ct ON c.barangayID = bs.cityID
            WHERE complaintStatus = 'Pending'";
            $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<thead>
        <tr>
            <th>Complaint ID</th>
            <th>Complainant Name</th>
            <th>Complainant Cellphone No.</th>
            <th>Complaint Date and Time</th>
            <th>Complaint Address</th>
            <th>City</th>
            <th>Barangay</th>
            <th>Complaint Details</th>
            <th>Type of Complaint</th>
            <th>Priority</th>
            <th>Complaint Status</th>
        </tr>
    </thead>";

        while ($row = $result->fetch_assoc()) {
            echo "<tbody>";
            echo "<tr>";
            echo "<tr>";
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

            echo "<td>";
            echo "<button class='btn btn-primary view-btn' data-toggle='modal' data-target='#viewModal' data-complaint-id='" . $row["complaintID"] . "'>View</button>";
            echo '<button class="' . $btnarchive . '">Archive</button>';
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No complaints found.";
    }
    $conn->close();
            ?>
        </div>
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
                    <p><strong>Complaintnant Name:</strong> <span id="complainantName"></span></p>
                    <p><strong>Complainant Cellphone Number:</strong> <span id="complainantCellphoneNo"></span></p>
                    <p><strong>Complaint Date and Time:</strong> <span id="complaintDateAndTime"></span></p>
                    <p><strong>Complaint Address:</strong> <span id="complaintAddress"></span></p>
                    <p><strong>City:</strong> <span id="city"></span></p>
                    <p><strong>Barangay:</strong> <span id="barangay"></span></p>
                    <p><strong>Complaint Details:</strong> <span id="complaintDetails"></span></p>
                    <p><strong>Type of Complaint:</strong> <span id="complaintType"></span></p>
                    <p><strong>Priority Level:</strong> <span id="priorityLevel"></span></p>
                    <p><strong>Complaint Status:</strong> <span id="complaintStatus"></span></p>
                    <p><strong>Complaint Evidence:</strong> <span id="complaintEvidence"></span></p>
                    <p><strong>Remarks:</strong> <span id="remarks"></span></p>
                    <!-- insert input codes here-->
                    <p><strong>Remarks Evidence:</strong> <span id="remarksEvidence"></span></p>
                    <!-- insert input codes here-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Update Complaint</button>
                    <!--update button-->
                    <br>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Print</button>
                    <br>
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
                var complainantName = $(this).closest('tr').find('td:nth-child(2)').text();
                var complainantCellphoneNo = $(this).closest('tr').find('td:nth-child(3)').text();
                var complaintDateAndTime = $(this).closest('tr').find('td:nth-child(4)').text();
                var complaintAddress = $(this).closest('tr').find('td:nth-child(5)').text();
                var city = $(this).closest('tr').find('td:nth-child(6)').text();
                var barangay = $(this).closest('tr').find('td:nth-child(7)').text();
                var complaintDetails = $(this).closest('tr').find('td:nth-child(8)').text();
                var complaintType = $(this).closest('tr').find('td:nth-child(9)').text();
                var priorityLevel = $(this).closest('tr').find('td:nth-child(10)').text();
                var complaintStatus = $(this).closest('tr').find('td:nth-child(11)').text();
                var complaintEvidence = $(this).closest('tr').find('').text();    
                var remarks = $(this).closest('tr').find('').text();
                var remarksEvidence = $(this).closest('tr').find('').text();

                $('#complaintID').text(complaintID);
                $('#complainantName').text(complainantName);
                $('#complainantCellphoneNo').text(complainantCellphoneNo);
                $('#complaintDateAndTime').text(complaintDateAndTime);
                $('#complaintAddress').text(complaintAddress);
                $('#city').text(city);
                $('#barangay').text(barangay);
                $('#complaintDetails').text(complaintDetails);
                $('#complaintType').text(complaintType);
                $('#priorityLevel').text(priorityLevel);
                $('#complaintStatus').text(complaintStatus);
                $('#complaintEvidence').text(complaintEvidence);
                $('#remarks').text(remarks);
                $('#remarksEvidence').text(remarksEvidence);
            });
        });
    </script>


</body>
</body>
</html>