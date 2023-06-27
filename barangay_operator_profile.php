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
                <div class="profile-name">Juan Dela Cruz</div> <!-- THIS SHOULD BE FROM SESSION -->
                <div class="profile-title">Barangay Operator</div>
            </div>
            <div class="tabs">
            <a href="barangay_operator_profile.php"><div class="tab active">Profile</div></a>
                <a href="admin.php"><div class="tab">Pending Complaints</div></a>
                <a href="adminProcessing.php"><div class="tab">Processing Complaints</div></a>
                <a href="adminComplete.php"><div class="tab">Completed Complaints</div></a>
                <a href="index.php"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>

        </div>
        <div class="content">
        <h2>Operator Profile</h2>

            <?php
                // UPDATE OPERATOR PROFILE
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $brgyOperatorID = $_POST["brgyOperatorID"];
                        $firstName = $_POST["firstName"];
                        $lastName = $_POST["lastName"];
                        $middleName = $_POST["middleName"];
                        $emailAddress = $_POST["emailAddress"];
                        $streetAddress = $_POST["streetAddress"];
                        $city = $_POST["city"];
                        $barangay = $_POST["barangay"];
                        $cellphoneNumber = $_POST["cellphoneNumber"];
                        $password = $_POST["password"];


                    $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "UPDATE user u
                            INNER JOIN barangay_operator bo ON u.userID = bo.userID 
                            SET u.firstName = '$firstName', 
                                u.lastName = '$lastName',
                                u.middleName = '$middleName',
                                u.emailAddress = '$emailAddress',
                                u.streetAddress = '$streetAddress',
                                u.city = '$city',
                                u.barangay = '$barangay',
                                u.cellphoneNumber = '$cellphoneNumber',
                                u.password = '$password'
                            WHERE bo.brgyOperatorID = '2'";
                    if ($conn->query($sql) === TRUE) {
                        echo "<h3>Profile updated successfully!</h3>";
                    } else {
                        echo "Error updating complaint: " . $conn->error;
                    }

                    $conn->close();
                    }
            ?>

                <?php
                $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT bo.brgyOperatorID, u.userID, u.firstName, u.lastName, u.middleName, u.emailAddress, u.streetAddress, u.city, u.barangay, u.cellphoneNumber, u.password, u.accountType, u.accountStatus,
                               bs.barangayName, c.cityName
                        FROM user u
                        INNER JOIN barangay_operator bo ON u.userID = bo.userID
                        INNER JOIN barangay_station bs ON bo.barangayID = bs.barangayID
                        INNER JOIN city c ON bs.cityID = c.cityID
                        WHERE brgyOperatorID = '2'"; //THIS CODE SHOULD BE FROM THE SESSION
                $result = $conn->query($sql);

                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $brgyOperatorID = $row["brgyOperatorID"];
                        $userID = $row["userID"];
                        $firstName = $row["firstName"];
                        $lastName = $row["lastName"];
                        $middleName = $row["middleName"];
                        $emailAddress = $row["emailAddress"];
                        $streetAddress = $row["streetAddress"];
                        $city = $row["city"];
                        $barangay = $row["barangay"];
                        $cellphoneNumber = $row["cellphoneNumber"];
                        $password = $row["password"];
                        $accountType = $row["accountType"];
                        $accountStatus = $row["accountStatus"];
                        $barangayName = $row["barangayName"];
                        $cityName = $row["cityName"];

                        //ADD DISPLAY HERE
                        echo "
                        
                        <div class='form-group'>
                        <label for='FirstName'>First Name</label>
                        <input type='text' class='form-control' value='$firstName' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Last Name</label>
                        <input type='text' class='form-control' value='$lastName' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Middle Name</label>
                        <input type='text' class='form-control' value='$middleName' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Email Address</label>
                        <input type='email' class='form-control' value='$emailAddress' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Street Address</label>
                        <input type='text' class='form-control' value='$streetAddress' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>City</label>
                        <input type='text' class='form-control' value='$city' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Barangay</label>
                        <input type='text' class='form-control' value='$barangay' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Cellphone Number</label>
                        <input type='text' class='form-control' value='$cellphoneNumber' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Password</label>
                        <input type='password' class='form-control' value='$password' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Account Type</label>
                        <input type='text' class='form-control' value='$accountType' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Barangay Station</label>
                        <input type='text' class='form-control' value='$barangayName' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>City Station</label>
                        <input type='text' class='form-control' value='$cityName' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Account Status</label>
                        <input type='text' class='form-control' value='$accountStatus' readonly>
                        </div>

                        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$brgyOperatorID'>
                                        Edit Profile
                        </button>
                    
                        ";
                        //MODAL UP HERE

                        //POP UP MODAL
                        echo "<div class='modal fade' id='myModal$brgyOperatorID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='myModalLabel'>Complaint Details</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>


                                        <form method='POST' action=''>
                                        <input type='hidden' name='brgyOperatorID' value='$brgyOperatorID'>

                        <div class='form-group'>
                        <label for='FirstName'>First Name</label>
                        <input type='text' class='form-control' value='$firstName' name='firstName'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Last Name</label>
                        <input type='text' class='form-control' value='$lastName' name='lastName'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Middle Name</label>
                        <input type='text' class='form-control' value='$middleName' name='middleName'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Email Address</label>
                        <input type='email' class='form-control' value='$emailAddress' name='emailAddress'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Street Address</label>
                        <input type='text' class='form-control' value='$streetAddress' name='streetAddress'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>City</label>
                        <input type='text' class='form-control' value='$city' name='city'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Barangay</label>
                        <input type='text' class='form-control' value='$barangay' name='barangay'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Cellphone Number</label>
                        <input type='text' class='form-control' value='$cellphoneNumber' name='cellphoneNumber'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Password</label>
                        <input type='password' class='form-control' value='$password' name='password'>
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
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>