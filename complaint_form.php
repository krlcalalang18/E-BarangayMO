<?php
session_start();

if (!isset($_SESSION['citizenID'])){

    header("Location: citizen_session_error_page.php");
}

?>

<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "ebarangaydatabase";

// Create connection
$connection = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Default values


$testSession = $_SESSION['citizenID'];
$sql = "SELECT citizenID 
        FROM user u
        INNER JOIN citizen c ON c.userID = u.userID
        WHERE u.userID = '$testSession'";
$result = mysqli_query($connection, $sql);

if($result->num_rows >0){
    $row = $result->fetch_assoc();
    $citizenID = $row['citizenID'];
}


$priorityLevel = "Normal";
$complaintStatus = "Pending";

// Get current date and time
$currentDateTime = date("Y-m-d H:i:s");

// Fetch cities from the database
$sql = "SELECT * FROM city";
$result = mysqli_query($connection, $sql);

// Fetch barangays based on selected city
if (isset($_POST['city'])) {
    $selectedCityID = $_POST['city'];
    $barangaySql = "SELECT * FROM barangay_station WHERE cityID = '$selectedCityID'";
    $barangayResult = mysqli_query($connection, $barangaySql);
}

// Insert complaint into the database
if (isset($_POST['submit'])) {
    $complaintType = $_POST['complaintType'];
    $complaintAddress = $_POST['complaintAddress'];
    $barangayID = $_POST['barangayID'];
    $complaintDetails = $_POST['complaintDetails'];
    $complaintEvidence = $_FILES['complaintEvidence'];

    // Check if file was uploaded successfully
    if ($complaintEvidence['error'] === UPLOAD_ERR_OK) {
        $tmpFilePath = $complaintEvidence['tmp_name'];

        // Read the file and convert it to binary data
        $fp = fopen($tmpFilePath, 'r');
        $complaintEvidenceData = fread($fp, filesize($tmpFilePath));
        fclose($fp);

        // Escape special characters in the binary data
        $escapedComplaintEvidenceData = mysqli_real_escape_string($connection, $complaintEvidenceData);

        // Insert the complaint into the database
        $insertSql = "INSERT INTO complaint (citizenID, complaintType, complaintAddress, barangayID, complaintDetails, complaintDateAndTime, priorityLevel, complaintEvidence, complaintStatus) VALUES ('$citizenID', '$complaintType', '$complaintAddress', '$barangayID', '$complaintDetails', '$currentDateTime', '$priorityLevel', '$escapedComplaintEvidenceData', '$complaintStatus')";
        $insertResult = mysqli_query($connection, $insertSql);

        if ($insertResult) {
            // Complaint submitted successfully
            header("Location: complaint_success.php");
        } else {
            // Failed to submit complaint
            echo "Error: " . mysqli_error($connection);
        }
    } else {
        // no pic?
        $insertSql = "INSERT INTO complaint (citizenID, complaintType, complaintAddress, barangayID, complaintDetails, complaintDateAndTime, priorityLevel, complaintStatus) VALUES ('$citizenID', '$complaintType', '$complaintAddress', '$barangayID', '$complaintDetails', '$currentDateTime', '$priorityLevel', '$complaintStatus')";
        $insertResult = mysqli_query($connection, $insertSql);

        if ($insertResult) {
            // Complaint submitted successfully
            header("Location: complaint_success.php");
        } else {
            // Failed to submit complaint
            echo "Error: " . mysqli_error($connection);
        }
        
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Complaint Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                
                } else {
                }   

                if(isset($_POST['logout'])){
                    unset($_SESSION['citizenID']);
                    header("Location: citizen_login_page.html");
                }
                $conn->close();
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
    </nav>
    <div class="container">
        <h1 class="mt-3">Complaint Form</h1>
        <form action="complaint_form.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="complaintType" class="form-label">Complaint Type</label>
                <select class="form-control" id="complaintType" name="complaintType" required>
                    <option selected disabled>Select a Complaint Type</option>
                    <option value="Public Disturbance">Public Disturbance</option>
                    <option value="Noise Complaint">Noise Complaint</option>
                    <option value="Theft">Theft</option>
                    <option value="Cybercrime">Cybercrime</option>
                    <option value="Barangay Assistance">Barangay Assistance</option>
                    <option value="Police Assistance">Police Assistance</option>
            </select>
            </div>
            <div class="mb-3">
                <label for="complaintAddress" class="form-label">Complaint Address</label>
                <input type="text" class="form-control" id="complaintAddress" name="complaintAddress" placeholder="House #, Street, Subdivision/Village...."required>
            </div>
            <div class="mb-3">
                <label for="cityID" class="form-label">City</label>
                <select class="form-control" id="cityID" name="cityID" required>
                    <option selected disabled>Select City</option>
                    <?php
                    $host = "localhost";
                    $dbUsername = "root";
                    $dbPassword = "";
                    $dbName = "ebarangaydatabase";

                    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT cityID, cityName FROM city";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['cityID'] . "'>" . $row['cityName'] . "</option>";
                        }
                    }
                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="barangayID" class="form-label">Barangay</label>
                <select class="form-control" id="barangayID" name="barangayID" required>
                    <option selected disabled>Select Barangay</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="complaintDetails" class="form-label">Complaint Details</label>
                <textarea class="form-control" id="complaintDetails" name="complaintDetails" rows="5" required></textarea>
            </div>
            <input type="hidden" id="complaintDateAndTime" name="complaintDateAndTime" value="<?php echo date('Y-m-d H:i:s'); ?>">

            <div class="mb-3">
                <label for="complaintEvidence" class="form-label">Complaint Evidence</label>
                <input type="file" class="form-control-file" id="complaintEvidence" name="complaintEvidence" accept="image/*">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateBarangayOptions() {
            var cityID = document.getElementById("cityID").value;
            var barangayIDSelect = document.getElementById("barangayID");
            
            while (barangayIDSelect.firstChild) {
                barangayIDSelect.removeChild(barangayIDSelect.firstChild);
            }

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_barangay.php?cityID=" + cityID, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var barangays = JSON.parse(xhr.responseText);

                    if (barangays.length > 0) {
                        barangays.forEach(function (barangay) {
                            var option = document.createElement("option");
                            option.value = barangay.barangayID;
                            option.textContent = barangay.barangayName;
                            barangayIDSelect.appendChild(option);
                        });
                    }
                }
            };
            xhr.send();
        }

        document.getElementById("cityID").addEventListener("change", updateBarangayOptions);

        updateBarangayOptions();
    </script>
</body>
</html>
