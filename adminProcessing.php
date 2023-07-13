<?php
session_start();

if (!isset($_SESSION['sessionBrgyOperatorID'])){

    header("Location: session_error_page.php");
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Barangay Operator - Processing Complaints</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBYtCKKHW2orUxnry0Vyht44abg2YeGjIU&callback=loaded'></script>
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

<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile">
                <div class="profile-picture"></div>
                <div class="profile-name">

                <?php 
                
                $testSession = $_SESSION['sessionBrgyOperatorID'];
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT firstName, lastName FROM user WHERE userID = '$testSession' AND accountType = 'Barangay Operator'";
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

                </div>
                <div class="profile-title">Barangay Operator</div>
            </div>
            <div class="tabs">
                <a href="barangay_operator_profile.php"><div class="tab">Profile</div></a>
                <a href="admin.php"><div class="tab">Pending Complaints</div></a>
                <a href="adminProcessing.php"><div class="tab active">Processing Complaints</div></a>
                <a href="adminComplete.php"><div class="tab">Completed Complaints</div></a>
                <a href="adminUnfulfilled.php"><div class="tab">Unfulfilled Complaints</div></a>
                <a href="logout.php"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>

        </div>
        <?php
            $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $search = '';
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
            }
            // Pagination
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $perPage = isset($_GET['show']) ? $_GET['show'] : 10;
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            $offset = ($page - 1) * $perPage;
            ?>
        <div class="content">
        <h2>Citizen Verification</h2>
         <!-- Search Bar -->
    <form action="" method="GET">
        <div class="form-group">
            <input type="text" class="form-control" name="search" placeholder="Search" value="<?php echo $search; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
    <!-- Show Entries -->
    <div class="form-group">
        <label for="show">Show:</label>
        <select class="form-control" name="show" onchange="this.form.submit()">
            <option value="10" <?php if ($perPage == 10) echo 'selected'; ?>>10</option>
            <option value="20" <?php if ($perPage == 20) echo 'selected'; ?>>20</option>
            <option value="50" <?php if ($perPage == 50) echo 'selected'; ?>>50</option>
        </select>
    </div>
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

                if (isset($_POST["updateMe"]) && isset($_POST["complaintID"])) {
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

                            $brgyID = $_SESSION['sessionBrgyOperatorID'];
                        $operation = 'Updated a Complaint';
                        $dateAndTime = date('Y-m-d H:i:s');

                        $sqlGetOperatorID = "SELECT brgyOperatorID FROM barangay_operator WHERE userID = $brgyID";
                        $resultGetOperatorID = $conn->query($sqlGetOperatorID);

                        $rowGetOperatorID = $resultGetOperatorID->fetch_assoc();

                        $logBrgy = $rowGetOperatorID['brgyOperatorID'];

                        $sqlLog = "INSERT INTO logs_table (operation, dateAndTime, brgyOperatorID, complaintID) VALUES ('$operation', '$dateAndTime', '$logBrgy', '$complaintID')";
                        $resultLog = $conn->query($sqlLog);
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

                            $brgyID = $_SESSION['sessionBrgyOperatorID'];
                        $operation = 'Updated a Complaint';
                        $dateAndTime = date('Y-m-d H:i:s');

                        $sqlGetOperatorID = "SELECT brgyOperatorID FROM barangay_operator WHERE userID = $brgyID";
                        $resultGetOperatorID = $conn->query($sqlGetOperatorID);

                        $rowGetOperatorID = $resultGetOperatorID->fetch_assoc();

                        $logBrgy = $rowGetOperatorID['brgyOperatorID'];

                        $sqlLog = "INSERT INTO logs_table (operation, dateAndTime, brgyOperatorID, complaintID) VALUES ('$operation', '$dateAndTime', '$logBrgy', '$complaintID')";
                        $resultLog = $conn->query($sqlLog);
                            echo "Complaint updated successfully.";
                        } else {
                            echo "Error updating complaint.";
                        }

                        mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);

    if(isset($_POST['archiveMe'])){

        $complaintID = $_POST["DcomplaintID"];
        $Dstatus = $_POST["Dstatus"];

        $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $sql2 = "UPDATE complaint 
                SET complaintStatus = 'Archived'
                WHERE complaintID = $complaintID";
        if ($conn->query($sql2) === TRUE) {
            
            $sql3 = "INSERT INTO archived_complaint (complaintID)
                     SELECT complaintID
                     FROM complaint
                     WHERE complaintID = $complaintID";
                if ($conn->query($sql3) == TRUE) {

                    $brgyID = $_SESSION['sessionBrgyOperatorID'];
                        $operation2 = 'Archived a Complaint';
                        $dateAndTime = date('Y-m-d H:i:s');

                        $sqlGetOperatorID = "SELECT brgyOperatorID FROM barangay_operator WHERE userID = $brgyID";
                        $resultGetOperatorID = $conn->query($sqlGetOperatorID);

                        $rowGetOperatorID = $resultGetOperatorID->fetch_assoc();

                        $logBrgy = $rowGetOperatorID['brgyOperatorID'];

                        $sqlLog = "INSERT INTO logs_table (operation, dateAndTime, brgyOperatorID) VALUES ('$operation2', '$dateAndTime', '$logBrgy')";
                        $resultLog = $conn->query($sqlLog);
                    echo "Complaint archived successfully!";
                } 
                else {
                    echo "Error updating complaint.";
                }

        } else {
            echo "Error updating complaint.";
        }
        $conn->close();
    }
    ?>


<?php
$conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$sql = "SELECT c.complaintID, CONCAT(u.firstName, ' ', u.lastName) AS ComplainantName, u.cellphoneNumber AS ComplainantCellphoneNo, c.complaintDateAndTime, c.complaintAddress, ct.cityName AS City, bs.barangayName AS Barangay, c.complaintDetails, c.complaintType, c.priorityLevel, c.complaintStatus, c.complaintEvidence, c.remarks, c.remarksEvidence, c.longitude, c.latitude
FROM complaint c
INNER JOIN citizen ctn ON ctn.citizenID = c.citizenID
INNER JOIN user u ON ctn.userID = u.userID
INNER JOIN barangay_station bs ON bs.barangayID = c.barangayID
INNER JOIN city ct ON ct.cityID = bs.cityID
WHERE c.complaintStatus = 'Processing'";

// Add search condition
if (!empty($search)) {
    $sql .= " AND (
        c.complaintID LIKE '%$search%' OR
        CONCAT(u.firstName, ' ', u.lastName) LIKE '%$search%' OR
        u.cellphoneNumber LIKE '%$search%' OR
        c.complaintDateAndTime LIKE '%$search%' OR
        c.complaintAddress LIKE '%$search%' OR
        ct.cityName LIKE '%$search%' OR
        bs.barangayName LIKE '%$search%' OR
        c.complaintDetails LIKE '%$search%' OR
        c.complaintType LIKE '%$search%' OR
        c.priorityLevel LIKE '%$search%' OR
        c.complaintStatus LIKE '%$search%' OR
        c.remarks LIKE '%$search%'
    )";
}

            $sql .= " ORDER BY c.complaintID DESC";

            $totalRowsResult = $conn->query($sql);
            $totalRows = $totalRowsResult->num_rows;

            $totalPages = ceil($totalRows / $perPage);

            // Add pagination to the query
            $sql .= " LIMIT $offset, $perPage";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Fetch data
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
                        $longitude = $row["longitude"];
                        $latitude = $row["latitude"];
                        

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
                                    <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteModal$complaintID'>
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

                                        ";

                                        if ($latitude == !null && $longitude == !null){
                                            echo "
                                            
                                            <div class='form-group'>
                                        <label for='remarks'>longitude</label>
                                        <input type='text' class='form-control' value='$longitude' readonly>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>latitude</label>
                                        <input type='text' class='form-control' value='$latitude' readonly>
                                        </div>

                                        <div class='form-group'>
                                            <div id='map$complaintID' style='width: 400px; height: 300px;'></div>
                                        </div>

                                        <script>
                                            function initMap$complaintID() {
                                                var myLatLng = {lat: $latitude, lng: $longitude};
                                                var map = new google.maps.Map(document.getElementById('map$complaintID'), {
                                                    zoom: 16,
                                                    center: myLatLng
                                                });
                                                var marker = new google.maps.Marker({
                                                    position: myLatLng,
                                                    map: map,
                                                    title: 'Complaint Location'
                                                });
                                            }
                                            google.maps.event.addDomListener(window, 'load', initMap$complaintID);
                                        </script>
                                        
                                        ";
                                        }
                                        else {
                                            echo "";
                                        }
                                        echo "

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

                                        ";

                                        if ($complaintEvidence == !null){
                                            echo "<div class='form-group'>
                                            <label for='remarks'>Evidence:</label>
                                            <img src='data:image/jpeg;base64,$complaintEvidence' style='width: 100%; height: 400px;'>
                                        </div>";
                                        }
                                        else {
                                            echo "";
                                        }

                                        echo"


                                        <div class='form-group'>
                                        <label for='remarks'>Complaint Type</label>
                                        <input type='text' class='form-control' rows='3' value='$complaintType' readonly>
                                        </div>             

                                            <form method='POST' action='adminProcessing.php' enctype='multipart/form-data'>
                                                <input type='hidden' name='complaintID' value='$complaintID'>

                                                <div class='form-group'>
                                                    <label for='status'>Status</label>
                                                    <select class='form-control' name='complaintStatus'>
                                                        <option value='Pending' " . ($complaintStatus == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                        <option value='Processing' " . ($complaintStatus == 'Processing' ? 'selected' : '') . ">Processing</option>
                                                        <option value='Complete' " . ($complaintStatus == 'Complete' ? 'selected' : '') . ">Complete</option>
                                                        <option value='Unfulfilled' " . ($complaintStatus == 'Unfulfilled' ? 'selected' : '') . ">Unfulfilled</option>
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
                                                <button type='submit' name='updateMe' class='btn btn-primary'>Update</button>
                                                <button type='button' class='btn btn-success'>Print</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>";

                            //DELETE MODAL
                            echo "<div class='modal fade' id='deleteModal$complaintID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='myModalLabel'>Delete Confirmation</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>

                                            <form method='POST' action='adminProcessing.php'>
                                            <input type='hidden' name='DcomplaintID' value='$complaintID'>
                                            <input type='hidden' name='Dstatus' value='$complaintStatus'>

                                                <h1> Are you sure you want to archive this record? </h1>

                                                <button type='submit' name='archiveMe' class='btn btn-danger'>Delete</button>
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
        <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php
            // Previous page
            if ($page > 1) {
                echo "<li class='page-item'><a class='page-link' href='adminProcessing.php?page=" . ($page - 1) . "&show=$perPage&search=$search'>Previous</a></li>";
            }

            // Page numbers
            for ($i = 1; $i <= $totalPages; $i++) {
                echo "<li class='page-item " . ($i == $page ? 'active' : '') . "'><a class='page-link' href='adminProcessing.php?page=$i&show=$perPage&search=$search'>$i</a></li>";
            }

            // Next page
            if ($page < $totalPages) {
                echo "<li class='page-item'><a class='page-link' href='adminProcessing.php?page=" . ($page + 1) . "&show=$perPage&search=$search'>Next</a></li>";
            }
            ?>
        </ul>
    </nav>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>