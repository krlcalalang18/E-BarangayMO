<!DOCTYPE html>
<html>
<head>
    <title>Barangay Operator - Processing Complaints</title>
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
            width: auto;
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

        .tab.logout {
            background-color: #FF0000;
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
                <a href="barangay_operator_profile.php"><div class="tab">Profile</div></a>
                <a href="admin.php"><div class="tab">Pending Complaints</div></a>
                <a href="adminProcessing.php"><div class="tab active">Processing Complaints</div></a>
                <a href="adminComplete.php"><div class="tab">Completed Complaints</div></a>
                <a href="index.php"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>

        </div>
        <div class="content">
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
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php



                //DIFFERENT APPROACH FOR WITH UPLOADING FILES
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "ebarangaydatabase";
                $conn = mysqli_connect($servername, $username, $password, $database);

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["complaintID"])) {
                    $complaintID = $_POST["complaintID"];
                    $complaintStatus = $_POST["complaintStatus"];
                    $remarks = $_POST["remarks"];
                    $priorityLevel = $_POST["priorityLevel"];

                if (isset($_FILES["remarksEvidence"]) && $_FILES["remarksEvidence"]["error"] === UPLOAD_ERR_OK) {
                        $file = $_FILES["remarksEvidence"];
                        $fileName = $file["name"];
                        $fileTmpPath = $file["tmp_name"];
                        $fileSize = $file["size"];

                        $fileContent = file_get_contents($fileTmpPath);

                        $sql = "UPDATE complaint SET complaintStatus = ?, remarks = ?, priorityLevel = ?, remarksEvidence = ? WHERE complaintID = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "sssbi", $complaintStatus, $remarks, $priorityLevel, $fileContent, $complaintID);
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
    }

    mysqli_close($conn);
    ?>

                <?php
                $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                

                $sql = "SELECT DISTINCT c.complaintID, CONCAT(u.firstName, ' ', u.lastName) AS ComplainantName, u.cellphoneNumber AS ComplainantCellphoneNo, c.complaintDateAndTime, c.complaintAddress, ct.cityName AS City, bs.barangayName AS Barangay, c.complaintDetails, c.complaintType, c.priorityLevel, c.complaintStatus, c.complaintEvidence, c.remarks, c.remarksEvidence
                        FROM complaint c
                        INNER JOIN user u ON c.citizenID = u.userID
                        INNER JOIN barangay_station bs ON c.barangayID = bs.barangayID
                        INNER JOIN city ct ON c.barangayID = bs.cityID
                        WHERE complaintStatus = 'Processing'";
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
                                <td>$remarks</td>
                                <td>
                                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$complaintID'>
                                        View
                                    </button>
                                </td>
                                <td>
                                    <button type='button' class='btn btn-danger'>
                                        Archive
                                    </button>
                                </td>
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
                                        <label for='remarks'>Complaint Type</label>
                                        <input type='text' class='form-control' rows='3' value='$complaintType' readonly>
                                        </div>             

                                            <form method='POST' action='' enctype='multipart/form-data'>
                                                <input type='hidden' name='complaintID' value='$complaintID'>

                                                <div class='form-group'>
                                                    <label for='status'>Status</label>
                                                    <select class='form-control' name='complaintStatus'>
                                                        <option value='Pending' " . ($complaintStatus == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                        <option value='Processing' " . ($complaintStatus == 'Processing' ? 'selected' : '') . ">Processing</option>
                                                        <option value='Complete' " . ($complaintStatus == 'Complete' ? 'selected' : '') . ">Complete</option>
                                                    </select>
                                                </div>

                                                <div class='form-group'> 
                                                    <label for='priorityLevel'>Priority</label>
                                                    <select class='form-control' name='priorityLevel'>
                                                        <option value='Normal' " . ($priorityLevel == 'Normal' ? 'selected' : '') . ">Normal</option>
                                                        <option value='High' " . ($priorityLevel == 'High' ? 'selected' : '') . ">High</option>
                                                    </select>
                                                </div>

                                                <div class='form-group'>
                                                    <label for='remarks'>Remarks</label>
                                                    <input type='text' class='form-control' name='remarks' value='$remarks'>
                                                </div>

                                                <div class='form-group'>
                                                    <label for='remarksEvidence'>Remarks Evidence</label>
                                                    <input type='file' class='form-control' name='remarksEvidence' id='remarksEvidence'>
                                                </div>
                                                <button type='submit' class='btn btn-primary'>Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                    }
                } else {
                    echo "<tr><td colspan='14'>No Records.</td></tr>";
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