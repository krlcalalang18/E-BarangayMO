<?php
session_start();

if (!isset($_SESSION['citizenID'])){

    header("Location: citizen_session_error_page.php");
}


?>
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

        .container {
            width: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a href="citizen_home.php"><span class="navbar-brand">

        <?php 
                //GET SESSION DETAILS CONVERT TO NAME 
                $testSession = $_SESSION['citizenID'];
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if(isset($_POST['logout'])){
                    unset($_SESSION['citizenID']);
                    header("Location: citizen_login_page.html");
                }

                $sql = "SELECT firstName, lastName FROM user WHERE userID = '$testSession' AND accountType = 'Citizen'";
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

        </span></a>
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
                         WHERE userID = '$testSession'
                         AND accountType = 'Citizen'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $SaccountStatus = $row['accountStatus'];

                if($SaccountStatus == 'Active'){
                    echo "Verified";
                }
                else {
                    echo "Pending";
                }
                
                }  
                ?>

                </span>
            </li>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="citizen_profile.php"><button class="btn btn-primary">Edit Profile</button></a>
            </li>
            &nbsp;
            <li class="nav-item">
                <form method="POST" action="citizen_profile.php">
                <button type="submit" name="logout" class="btn btn-danger">Log Out</button>
            </form>
            </li>
            </ul>
    </nav>

    <div class="container mt-4">
        <?php 
        
        if($SaccountStatus == 'Active'){
            echo "<a href='complaint_form.php'><button class='btn btn-info'>File a Complaint</button></a>";
        }
        else {
            echo "<h1>Your account is pending for approval before you can submit complaints.</h1>";

        }  
        $conn->close();
        ?>
        
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
                            <th>Status</th>
                            <th>Action</th>
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
                        WHERE u.userID = '$testSession'";
                        $result = $conn->query($sql);
                        
                        
                        if($SaccountStatus == 'Active'){
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
                                        <td>$complaintStatus</td>
                                        <td>
                                        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$complaintID'>
                                        View
                                    </button>
                                    <td>
                                    </tr>";

                                    //POP UP MODAL
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

                                <div class='form-group'>
                                <label for='remarks'>Complaint ID</label>
                                <input type='text' class='form-control' value='$complaintID' readonly>
                                </div>

                                <div class='form-group'>
                                <label for='remarks'>Complainant Name</label>
                                <input type='text' class='form-control' value='$complainantName' readonly>
                                </div>

                                <div class='form-group'>
                                <label for='remarks'>Complainant Cellphone Number</label>
                                <input type='text' class='form-control' value='$complainantCellphone' readonly>
                                </div>


                                <div class='form-group'>
                                <label for='remarks'>Complaint Date And Time</label>
                                <input type='text' class='form-control' value='$complaintDateAndTime' readonly>
                                </div>

                                <div class='form-group'>
                                <label for='remarks'>Complaint Address</label>
                                <input type='text' class='form-control' value='$complaintAddress' readonly>
                                </div>

                                <div class='form-group'>
                                <label for='remarks'>City</label>
                                <input type='text' class='form-control' value='$city' readonly>
                                </div>

                                <div class='form-group'>
                                <label for='remarks'>Barangay</label>
                                <input type='text' class='form-control' value='$barangay' readonly>
                                </div>
                                  

                                <div class='form-group'>
                                <label for='remarks'>Details</label>
                                <textarea class='form-control' rows='3' value='' readonly>$complaintDetails</textarea>
                                </div>

                                <div class='form-group'>
                                <label for='remarks'>Evidence:</label>
                                <img src='data:image/jpeg;base64,$complaintEvidence' style='width: 100px; height: 100px;'>
                                </div>
                                
                                <div class='form-group'>
                                <label for='remarks'>Complaint Status</label>
                                <input type='text' class='form-control' rows='3' value='$complaintStatus' readonly>
                                </div>   

                                <div class='form-group'>
                                <label for='remarks'>Remarks</label>
                                <input type='text' class='form-control' rows='3' value='$remarks' readonly>
                                </div>   

                                <div class='form-group'>
                                        <label for='remarks'>Remarks Evidence:</label>
                                        <img src='data:image/jpeg;base64,$remarksEvidence' style='width: 100px; height: 100px;'>
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>";
                            }
                        }
                    } else {

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
