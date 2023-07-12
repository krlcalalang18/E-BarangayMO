<?php
session_start();

if (!isset($_SESSION['LGUOperatorID'])){

    header("Location: session_error_page_LGU.php");
}

?>

<!DOCTYPE html>
<html>
<head>    <title>LGU Operator - Audit Logs</title>
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
                <a href="display_citizen.php"><div class="tab">Citizen Verification</div></a>
                <a href="audit_logs.php"><div class="tab active">Audit Logs</div></a>
                <a href="logout_LGU.php"><div class="tab logout">Log Out</div></a>
            </div>

        </div>
        <?php
$search = isset($_GET['search']) ? $_GET['search'] : '';
$entries = isset($_GET['entries']) ? intval($_GET['entries']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $entries;

$conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$entries = isset($_GET['entries']) ? intval($_GET['entries']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $entries;

$conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlCount = "SELECT COUNT(*) AS total
             FROM logs_table lt
             INNER JOIN barangay_operator bo ON bo.brgyOperatorID = lt.brgyOperatorID
             INNER JOIN user u ON u.userID = bo.userID
             WHERE CONCAT(u.firstName, ' ', u.lastName) LIKE '%$search%'
             OR lt.dateAndTime LIKE '%$search%'
             OR lt.operation LIKE '%$search%'
             OR lt.logID LIKE '%$search%'
             OR lt.complaintID LIKE '%$search%'";
$resultCount = $conn->query($sqlCount);
$row = $resultCount->fetch_assoc();
$total = $row['total'];
$numPages = ceil($total / $entries);

$sql = "SELECT lt.logID, CONCAT(u.firstName, ' ', u.lastName) AS operatorName, lt.operation, lt.dateAndTime, lt.complaintID
        FROM logs_table lt
        INNER JOIN barangay_operator bo ON bo.brgyOperatorID = lt.brgyOperatorID
        INNER JOIN user u ON u.userID = bo.userID
        WHERE CONCAT(u.firstName, ' ', u.lastName) LIKE '%$search%'
        OR lt.dateAndTime LIKE '%$search%'
        OR lt.operation LIKE '%$search%'
        OR lt.logID LIKE '%$search%'
        OR lt.complaintID LIKE '%$search%'
        ORDER BY lt.logID DESC
        LIMIT $entries OFFSET $offset";
$result = $conn->query($sql);

?>

<div class="content">
    <h2>Audit Logs</h2>

    <form method="GET" action="">
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <input type="text" class="form-control" name="search" placeholder="Search" value="<?php echo $search; ?>">
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-control" name="entries">
                    <option value="10" <?php echo ($entries == 10) ? 'selected' : ''; ?>>10</option>
                    <option value="20" <?php echo ($entries == 20) ? 'selected' : ''; ?>>20</option>
                    <option value="50" <?php echo ($entries == 50) ? 'selected' : ''; ?>>50</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Log ID</th>
                <th>Operator</th>
                <th>Action</th>
                <th>Date and Time</th>
                <th>Complaint ID</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $logID = $row['logID'];
                    $operatorName = $row['operatorName'];
                    $operation = $row['operation'];
                    $dateAndTime = $row['dateAndTime'];
                    $complaintID = $row['complaintID'];

                    if ($complaintID == 0 || $complaintID == null) {
                        $complaintID = 'N/A';
                    }

                    echo "<tr>
                            <td>$logID</td>
                            <td>$operatorName</td>
                            <td>$operation</td>
                            <td>$dateAndTime</td>
                            <td>$complaintID</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No results found.</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php
            if ($numPages > 1) {
                for ($i = 1; $i <= $numPages; $i++) {
                    $isActive = ($i == $page) ? 'active' : '';
                    echo "<li class='page-item $isActive'><a class='page-link' href='?page=$i&search=$search&entries=$entries'>$i</a></li>";
                }
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