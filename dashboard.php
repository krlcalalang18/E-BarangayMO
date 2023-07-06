<?php
session_start();

if (!isset($_SESSION['LGUOperatorID'])){

    header("Location: session_error_page_LGU.php");
}

?>

<!DOCTYPE html>
<html>
<head>    <title>LGU Operator - Dashboard</title>
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
                <a href="dashboard.php"><div class="tab active">Dashboard</div></a>
                <a href="display_citizen.php"><div class="tab">Citizen Verification</div></a>
                <a href=""><div class="tab">Audit Logs</div></a>
                <a href="logout_LGU.php"><div class="tab logout">Log Out</div></a>
            </div>

        </div>
        <div class="content">
        <h2>Dashboard</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pending Complaints</th>
                    <th>Processing Complaints</th>
                    <th>Completed Complaints</th>
                    <th>Unfulfilled Complaints</th>
                    <th>Overall Complaints</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                

                $sql2 = "SELECT COUNT(complaintStatus) AS totalPending
                        FROM complaint
                        WHERE complaintStatus = 'Pending'";
                $result2 = $conn->query($sql2);

                if ($result2->num_rows > 0) {
                    while ($row = $result2->fetch_assoc()) {

                        $totalPending = $row["totalPending"];

                        echo "<td>$totalPending</td>";

                    }
                } else {
                    echo "<tr><td colspan='14'>Error</td></tr>";
                }

                $sql3 = "SELECT COUNT(complaintStatus) AS totalProcessing
                        FROM complaint
                        WHERE complaintStatus = 'Processing'";
                $result3 = $conn->query($sql3);

                if ($result3->num_rows > 0) {
                    while ($row = $result3->fetch_assoc()) {

                        $totalProcessing = $row["totalProcessing"];

                        echo "<td>$totalProcessing</td>";

                    }
                } else {
                    echo "<tr>0</tr>";
                }

                $sql4 = "SELECT COUNT(complaintStatus) AS totalComplete
                        FROM complaint
                        WHERE complaintStatus = 'Complete'";
                $result4 = $conn->query($sql4);

                if ($result4->num_rows > 0) {
                    while ($row = $result4->fetch_assoc()) {

                        $totalComplete = $row["totalComplete"];

                        echo "<td>$totalComplete</td>";

                    }
                } else {
                    echo "<tr>0</tr>";
                }

                $sql5 = "SELECT COUNT(complaintStatus) AS totalUnfulfilled
                        FROM complaint
                        WHERE complaintStatus = 'Unfulfilled'";
                $result5 = $conn->query($sql5);

                if ($result5->num_rows > 0) {
                    while ($row = $result5->fetch_assoc()) {

                        $totalUnfulfilled = $row["totalUnfulfilled"];

                        echo "<td>$totalUnfulfilled</td>";

                    }
                } else {
                    echo "<tr>0</tr>";
                }

                $sql = "SELECT COUNT(complaintID) AS totalComplaint
                        FROM complaint";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $totalComplaints = $row["totalComplaint"];

                        echo "<td>$totalComplaints</td>";

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