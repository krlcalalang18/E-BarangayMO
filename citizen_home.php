<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <style>
        @media (min-width: 576px) {
            .navbar-nav {
                margin-left: auto;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <span class="navbar-brand">

        <?php 
                //GET SESSION DETAILS CONVERT TO NAME 
                //$testSession = $_SESSION['citizenID'];
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT firstName, lastName FROM user WHERE userID = '1' AND accountType = 'Citizen'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $SfirstName = $row['firstName'];
                $SlastName = $row['lastName'];

                echo "$SfirstName $SlastName";
                } else {
                }   
                $conn->close();
                ?>

        </span>
        <ul class="navbar-nav">
            <li class="nav-item">
                <span class="navbar-text">Account Status: 

                <?php 
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = " SELECT accountStatus 
                         FROM user 
                         WHERE userID = '1'
                         AND accountType = 'Citizen'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $SaccountStatus = $row['accountStatus'];

                if($SaccountStatus == 'Active'){
                    echo "Verified";
                }
                
                } else {
                }   
                $conn->close();
                ?>

                </span>
            </li>
        </ul>
    </nav>

    <div class="container mt-4">
        <button class="btn btn-primary" onclick="window.location.href='file_complaint.php'">File a Complaint</button>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3">Submitted Complaints</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Complaint Reference No.</th>
                            <th>Complaint Details</th>
                            <th>Date and Time Submitted</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Remarks Evidence</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
        
                        $sql = "SELECT c.complaintID, CONCAT(u.firstName, ' ', u.lastName) AS ComplainantName, u.cellphoneNumber AS ComplainantCellphoneNo, c.complaintDateAndTime, c.complaintAddress, ct.cityName AS City, bs.barangayName AS Barangay, c.complaintDetails, c.complaintType, c.priorityLevel, c.complaintStatus, c.complaintEvidence, c.remarks, c.remarksEvidence
                        FROM complaint c
                        INNER JOIN citizen ctn ON ctn.citizenID = c.citizenID
                        INNER JOIN user u ON ctn.userID = u.userID
                        INNER JOIN barangay_station bs ON bs.barangayID = c.barangayID
                        INNER JOIN city ct ON ct.cityID = bs.cityID
                        WHERE u.userID = '1'";
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
                                        <td>$complaintDetails</td>
                                        <td>$complaintDateAndTime</td>
                                        <td>$complaintStatus</td>
                                        <td>$remarks</td>
                                        <td><img src='data:image/jpeg;base64,$complaintEvidence' style='width: 100px; height: 100px;'><td>
                                    </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
