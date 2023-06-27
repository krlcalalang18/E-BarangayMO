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
                <div class="profile-name">Joseph Monroe Santos</div>
                <div class="profile-title">LGU Operator</div>
            </div>
            <div class="tabs">
                <a href=""><div class="tab">Profile</div></a>
                <a href=""><div class="tab">Dashboard</div></a>
                <a href="display_citizen.php"><div class="tab active">Citizen Verification</div></a>
                <a href=".php"><div class="tab">Audit Logs</div></a>
                <a href="index.php"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>

        </div>
        <div class="content">
        <h2>Citizen Verification</h2>
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
                        echo "Complaint updated successfully";
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

                $sql = "SELECT c.citizenID, c.idNumber, c.idType, c.idExpiry, c.idBirthday, c.idPicture, c.idSelfPhoto, u.userID,
                               u.firstName, u.lastName, u.middleName, u.emailAddress, u.streetAddress, u.city, u.barangay, u.accountStatus
                        FROM   citizen c
                        INNER JOIN user u ON c.userID = u.userID";
                $result = $conn->query($sql);

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
                                <td>
                                    <button type='button' class='btn btn-danger'>
                                        Archive
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
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>