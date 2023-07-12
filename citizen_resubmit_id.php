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

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3">Resubmit ID</h4>
                <h6 class="mb-3">Note: Resubmitting ID will be pending for approval before submitting complaints again. </h6>

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

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get the citizen ID from the form

    // Retrieve the citizen record from the database
    $selectSql = "SELECT * FROM citizen WHERE userID = '$testSession'";
    $result = mysqli_query($connection, $selectSql);

    if (mysqli_num_rows($result) > 0) {
        // Fetch the citizen record

        $testSession = $_SESSION['citizenID'];
        $citizen = mysqli_fetch_assoc($result);

        // Update the citizen's ID number, ID expiry, and ID birthday
        $idNumber = $_POST['idNumber'];
        $idExpiry = $_POST['idExpiry'];
        $idBirthday = $_POST['idBirthday'];
        $idType = $_POST['idType'];


        $updateSql = "UPDATE citizen c
                      INNER JOIN user u ON c.userID = u.userID
                      SET c.idNumber = '$idNumber', c.idType = '$idType', c.idExpiry = '$idExpiry', c.idBirthday = '$idBirthday', u.accountStatus = 'Pending'
                      WHERE c.userID = '$testSession'";

        if (mysqli_query($connection, $updateSql)) {
            echo "ID Submitted Successfully.";
        } else {
            echo "Error updating citizen record: " . mysqli_error($connection);
        }

        // Check if new ID picture is uploaded
        if ($_FILES['idPicture']['error'] === UPLOAD_ERR_OK) {
            $tmpFilePath = $_FILES['idPicture']['tmp_name'];
            $idPictureData = file_get_contents($tmpFilePath);

            $updatePictureSql = "UPDATE citizen SET idPicture = ? WHERE userID = '$testSession'";
            $stmt = mysqli_prepare($connection, $updatePictureSql);
            mysqli_stmt_bind_param($stmt, "s", $idPictureData);

            if (mysqli_stmt_execute($stmt)) {
            } else {
                echo "Error updating ID picture: " . mysqli_error($connection);
            }

        }

        // Check if new self-photo is uploaded
        if ($_FILES['idSelfPhoto']['error'] === UPLOAD_ERR_OK) {
            $tmpFilePath = $_FILES['idSelfPhoto']['tmp_name'];
            $idSelfPhotoData = file_get_contents($tmpFilePath);

            $updateSelfPhotoSql = "UPDATE citizen SET idSelfPhoto = ? WHERE userID = '$testSession'";
            $stmt = mysqli_prepare($connection, $updateSelfPhotoSql);
            mysqli_stmt_bind_param($stmt, "s", $idSelfPhotoData);

            if (mysqli_stmt_execute($stmt)) {
            } else {
                echo "Error updating self-photo: " . mysqli_error($connection);
            }

        }
    } else {
        echo "Citizen record not found.";
    }
}

// Close the database connection
mysqli_close($connection);
?>

<div class="container">
        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="idNumber">ID Number:</label>
                <input type="text" class="form-control" id="idNumber" name="idNumber" required>
            </div>

            <div class="form-group">
                <label for="idNumber">ID Type:</label>
                <input type="text" class="form-control" id="idType" name="idType" required>
            </div>

            <div class="form-group">
                <label for="idExpiry">ID Expiry:</label>
                <input type="date" class="form-control" id="idExpiry" name="idExpiry" required>
            </div>

            <div class="form-group">
                <label for="idBirthday">ID Birthday:</label>
                <input type="date" class="form-control" id="idBirthday" name="idBirthday" required>
            </div>

            <div class="form-group">
                <label for="idPicture">ID Picture:</label>
                <input type="file" class="form-control-file" id="idPicture" name="idPicture" required>
            </div>

            <div class="form-group">
                <label for="idSelfPhoto">Self-Photo:</label>
                <input type="file" class="form-control-file" id="idSelfPhoto" name="idSelfPhoto" required>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Resubmit ID</button>

        </form>
    </div>
                
                
            </div>
        </div>
    </div>

    

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
