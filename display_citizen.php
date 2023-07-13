<?php
session_start();


require 'C:/xampp/htdocs/testform/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/testform/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require 'C:/xampp/htdocs/testform/PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;




if (!isset($_SESSION['LGUOperatorID'])){

    header("Location: session_error_page_LGU.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>LGU Operator - Citizen Verification</title>
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
                <div class="profile-name">

                <?php 
                //GET SESSION DETAILS CONVERT TO NAME 
                $testSession = $_SESSION['LGUOperatorID'];
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT firstName, lastName FROM user WHERE userID = '$testSession' AND accountType = 'LGU Operator'";
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
                <div class="profile-title">LGU Operator</div>
            </div>
            <div class="tabs">
                <a href="lgu_operator_profile.php"><div class="tab">Profile</div></a>
                <a href="dashboard.php"><div class="tab">Dashboard</div></a>
                <a href="display_citizen.php"><div class="tab active">Citizen Verification</div></a>
                <a href="audit_logs.php"><div class="tab">Audit Logs</div></a>
                <a href="logout_LGU.php"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>

            <?php
$conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set default values
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = isset($_GET['show']) ? $_GET['show'] : 10;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Calculate start and offset for pagination
$start = ($page - 1) * $perPage;

// Count total records
$sqlCount = "SELECT COUNT(*) as total FROM citizen c INNER JOIN user u ON c.userID = u.userID 
             WHERE CONCAT(u.firstName, ' ', u.lastName) LIKE '%$search%' 
             OR u.emailAddress LIKE '%$search%'
             OR u.streetAddress LIKE '%$search%'
             OR u.city LIKE '%$search%'
             OR u.barangay LIKE '%$search%'
             OR c.idNumber LIKE '%$search%'
             OR c.idType LIKE '%$search%'
             OR c.idExpiry LIKE '%$search%'
             OR c.idBirthday LIKE '%$search%'
             OR u.accountStatus LIKE '%$search%'";
$countResult = $conn->query($sqlCount);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $perPage);

// Retrieve data with pagination
$sql = "SELECT c.citizenID, c.idNumber, c.idType, c.idExpiry, c.idBirthday, c.idPicture, c.idSelfPhoto, u.userID,
                u.firstName, u.lastName, u.middleName, u.emailAddress, u.streetAddress, u.city, u.barangay, u.accountStatus
        FROM citizen c
        INNER JOIN user u ON c.userID = u.userID
        WHERE CONCAT(u.firstName, ' ', u.lastName) LIKE '%$search%'
        OR u.emailAddress LIKE '%$search%'
        OR u.streetAddress LIKE '%$search%'
        OR u.city LIKE '%$search%'
        OR u.barangay LIKE '%$search%'
        OR c.idNumber LIKE '%$search%'
        OR c.idType LIKE '%$search%'
        OR c.idExpiry LIKE '%$search%'
        OR c.idBirthday LIKE '%$search%'
        OR u.accountStatus LIKE '%$search%'
        ORDER BY CASE WHEN u.accountStatus = 'Pending' THEN 1
                      WHEN u.accountStatus = 'Resubmit ID' THEN 2
                      WHEN u.accountStatus = 'Active' THEN 3
                      ELSE 4 END
        LIMIT $start, $perPage";
$result = $conn->query($sql);

?>
            
            

        </div>
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
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Name</th>
                    <th>Email Address</th>
                    <th>Street Address</th>
                    <th>City</th>
                    <th>Barangay</th>
                    <th>ID Number</th>
                    <th>ID Type</th>
                    <th>ID Expiry</th>
                    <th>ID Birthday</th>
                    <th>Account Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            



                <?php
                $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

               

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $userID = $row["userID"];
                        $citizenID = $row["citizenID"];
                        $firstName = $row["firstName"];
                        $lastName = $row["lastName"];
                        $middleName = $row["middleName"];
                        $emailAddress = $row["emailAddress"];
                        $streetAddress = $row["streetAddress"];
                        $city = $row["city"];
                        $barangay = $row["barangay"];
                        $idNumber = $row["idNumber"];
                        $idType = $row["idType"];
                        $idExpiry = $row["idExpiry"];
                        $idBirthday = $row["idBirthday"];
                        $idPicture = base64_encode($row["idPicture"]);
                        $idSelfPhoto = base64_encode($row["idSelfPhoto"]);
                        $accountStatus = $row["accountStatus"];


                        echo "<tr>
                                <td>$firstName</td>
                                <td>$lastName</td>
                                <td>$middleName</td>
                                <td>$emailAddress</td>
                                <td>$streetAddress</td>
                                <td>$city</td>
                                <td>$barangay</td>
                                <td>$idNumber</td>
                                <td>$idType</td>
                                <td>$idExpiry</td>
                                <td>$idBirthday</td>
                                <td>$accountStatus</td>
                                <td>
                                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$citizenID'>
                                        View
                                    </button>
                                </td>
                            </tr>";

                        //POP UP
                        echo "<div class='modal fade' id='myModal$citizenID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
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
                                        <label for='remarks'>Citizen ID</label>
                                        <input type='text' class='form-control' value='$citizenID' readonly>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>First Name</label>
                                        <input type='text' class='form-control' value='$firstName' readonly>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>Last Name</label>
                                        <input type='text' class='form-control' value='$lastName' readonly>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>Email Address</label>
                                        <input type='text' class='form-control' value='$emailAddress' readonly>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>Street Address</label>
                                        <input type='text' class='form-control' value='$streetAddress' readonly>
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
                                        <label for='remarks'>Valid ID Number</label>
                                        <input type='text' class='form-control' value='$idNumber' readonly>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>Valid ID Type</label>
                                        <input type='text' class='form-control' value='$idType' readonly>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>Valid ID Expiry</label>
                                        <input type='text' class='form-control' value='$idExpiry' readonly>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>Valid ID Birthday</label>
                                        <input type='text' class='form-control' value='$idBirthday' readonly>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>Valid ID Picture:</label>
                                        <img src='data:image/jpeg;base64,$idPicture' style='width: 160px; height: 120px;'>
                                        </div>

                                        <div class='form-group'>
                                        <label for='remarks'>ID Self Photo:</label>
                                        <img src='data:image/jpeg;base64,$idSelfPhoto' style='width: 160px; height: 120px;'>
                                        </div>

                                            <form method='POST' action=''>
                                                <input type='hidden' name='userID' value='$userID'>

                                                <div class='form-group'>
                                                    <label for='status'>Status</label>
                                                    <select class='form-control' name='status'>
                                                        <option value='Pending' " . ($accountStatus == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                        <option value='Active' " . ($accountStatus == 'Active' ? 'selected' : '') . ">Active</option>
                                                        <option value='Resubmit ID' " . ($accountStatus == 'Resubmit ID' ? 'selected' : '') . ">Resubmit ID</option>
                                                    </select>
                                                </div>
                                                <button type='submit' class='btn btn-primary'>Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                    }
                } else {
                    echo "<tr><td colspan='14'>Error</td></tr>";
                }
                $conn->close();
                ?>
                <?php
                // UPDATE DETAILS (NO MEDIA YET)
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $userID = $_POST["userID"];
                    $status = $_POST["status"];

                    $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "UPDATE user 
                            SET accountStatus = '$status'
                            WHERE userID = $userID";
                    if ($conn->query($sql) === TRUE) {
                        //send email to user saying your account has been approved
                        $sql2 = "SELECT emailAddress, accountStatus FROM user WHERE userID = $userID";
                        $result2 = $conn->query($sql2);
                        if($result2->num_rows > 0){
                            $row = $result2->fetch_assoc();
                            $emailSend = $row['emailAddress'];
                            $accountStat = $row['accountStatus'];

                            //SEND CONFIRMATION
                            if ($accountStat == 'Active'){
                                $message = 'Your account has been Verified! You may now log in to EBarangayMo!';
                            }
                            else if ($accountStat == 'Resubmit ID'){
                                $message = 'Please resubmit for ID Verification.';
                            }

                            else if ($accountStat == 'Pending'){
                                $message = 'Your is undergoing verification.';
                            }

                            $mail = new PHPMailer(true);

                            try {

                                $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'ebarangayhelp@gmail.com'; 
                            $mail->Password = 'dcweytfqyvjnbkas'; 
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;
                            
                    
                            $mail->setFrom('EbarangayHelp@gmail.com', 'ebarangay_emailer'); 
                            $mail->addAddress($emailSend); 
                    
                    
                                // Email content
                                $mail->isHTML(true);
                                $mail->Subject = 'Account Verification';
                                $mail->Body = $message;
                    
                                // Send the email
                                $mail->send();

                                //SEND TEXT
                    
                            } catch (Exception $e) {
                                // Error in sending OTP
                                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->Debugoutput = function ($str, $level) {
                        echo "Debug level $level; message: $str\n";
                    };
                    
                                
                                $error = "Failed to send OTP. Please try again.";
                            }


                        }


                        echo "Complaint updated successfully";
                    } else {
                        echo "Error updating complaint: " . $conn->error;
                    }
                    

                $conn->close();
                }
                ?>
            </tbody>
        </table>
        <!-- Pagination -->
    <ul class="pagination">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<li class='page-item" . ($page == $i ? ' active' : '') . "'>
                    <a class='page-link' href='?page=$i&show=$perPage&search=$search'>$i</a>
                  </li>";
        }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>