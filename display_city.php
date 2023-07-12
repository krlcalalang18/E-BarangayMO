<?php
session_start();

if (!isset($_SESSION['sessionAdminID'])){

    header("Location: session_error_page_admin.php");
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Cities</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <?php

    $conn = new mysqli('localhost','root','','ebarangaydatabase');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['updateMe'])) {
        $cityID = $_POST["cityID"];
        $cityName = $_POST["cityName"];
        $cityExpiry = $_POST["cityExpiry"];


        $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE city 
                SET cityName = '$cityName', cityExpiry = '$cityExpiry'
                WHERE cityID = $cityID";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "Error updating city.";
        }
        $conn->close();
        }

        if(isset($_POST['archiveMe'])){

            $DcityID = $_POST["DcityID"];

            $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }

            $sql2 = "DELETE FROM city
                     WHERE cityID = $DcityID";
            if ($conn->query($sql2) === TRUE) {

            } else {
                echo "Error deleting City.";
            }
            $conn->close();
        }


    ?>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile">
                <div class="profile-picture"></div>
                <div class="profile-name">


                <?php 
                //GET SESSION DETAILS CONVERT TO NAME 
                $testSession = $_SESSION['sessionAdminID'];
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT firstName, lastName FROM user WHERE userID = '$testSession' AND accountType = 'Administrator'";
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
                <div class="profile-title">Administrator</div>
            </div>
            <div class="tabs">
                <a href="admin_profile.php"><div class="tab">Profile</div></a>
                <a href="display_city.php"><div class="tab active">Cities</div></a>
                <a href="display_barangay.php"><div class="tab">Barangays</div></a>
                <a href="display_operator.php"><div class="tab">Barangay Operator Management</div></a>
                <a href="display_lgu_operator.php"><div class="tab">LGU Operator Management</div></a>
                <a href="adminBlockerPage.html"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>
        </div>
        <div class="content">
    <h1> Cities </h1>

    <div class="row mb-3">
        <div class="col-md-6">
            <form class="form-inline" method="GET" action="display_city.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="search" placeholder="Search City">
                </div>
                <button type="submit" class="btn btn-primary ml-2">Search</button>
            </form>
        </div>
        <div class="col-md-6 text-right">
            <form class="form-inline" method="GET" action="display_city.php">
                <div class="form-group">
                    <label for="entries">Show Entries:</label>
                    <select class="form-control mx-2" name="entries" onchange="this.form.submit()">
                        <option value="10" <?php if (isset($_GET['entries']) && $_GET['entries'] == 10) echo "selected"; ?>>10</option>
                        <option value="20" <?php if (isset($_GET['entries']) && $_GET['entries'] == 20) echo "selected"; ?>>20</option>
                        <option value="50" <?php if (isset($_GET['entries']) && $_GET['entries'] == 50) echo "selected"; ?>>50</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?php
    $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $search = isset($_GET['search']) ? $_GET['search'] : '';
$entries = isset($_GET['entries']) ? intval($_GET['entries']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $entries;

$sqlCount = "SELECT COUNT(*) AS total FROM city WHERE cityName LIKE '%$search%' OR cityExpiry LIKE '%$search%'";
$resultCount = $conn->query($sqlCount);
$row = $resultCount->fetch_assoc();
$total = $row['total'];
$numPages = ceil($total / $entries);

$sql = "SELECT * FROM city WHERE cityName LIKE '%$search%' OR cityExpiry LIKE '%$search%' LIMIT $entries OFFSET $offset";
$result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<table class='table table-bordered'>";
        echo "<thead>
                <tr>
                    <th>City Name</th>
                    <th>City Expiry</th>
                    <th>Action</th>
                </tr>
            </thead>";

        while ($row = $result->fetch_assoc()) {
            $cityName = $row["cityName"];
            $cityExpiry = $row["cityExpiry"];
            $cityID = $row["cityID"];
            $formattedDatetime = date('Y-m-d\TH:i', strtotime($cityExpiry));

            echo "<tr>";
            echo "<td>$cityName</td>";
            echo "<td>$cityExpiry</td>";
            echo "<td>
                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$cityID'>
                        Edit
                    </button>
                    <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteModal$cityID'>
                        Delete
                    </button>
                </td>";
            echo "</tr>";

            //POP UP MODAL
            echo "<div class='modal fade' id='myModal$cityID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                 <div class='modal-dialog' role='document'>
                     <div class='modal-content'>
                         <div class='modal-header'>
                             <h5 class='modal-title' id='myModalLabel'>Complaint Details</h5>
                             <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                 <span aria-hidden='true'>&times;</span>
                             </button>
                         </div>
                         <div class='modal-body'>
                             <form method='POST' action='display_city.php'>
                                 <input type='hidden' name='cityID' value='$cityID'>

                                 <div class='form-group'>
                                     <label for='cityName'>City Name</label>
                                     <input type='text' class='form-control' value='$cityName' name='cityName'>
                                 </div>

                                 <div class='form-group'>
                                     <label for='cityName'>City Expiry</label>
                                     <input type='datetime-local' class='form-control' value='$formattedDatetime' name='cityExpiry' required>
                                 </div>
                                 <button type='submit' class='btn btn-primary' name='updateMe'>Update</button>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>";

            //DELETE MODAL
            echo "<div class='modal fade' id='deleteModal$cityID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                 <div class='modal-dialog' role='document'>
                     <div class='modal-content'>
                         <div class='modal-header'>
                             <h5 class='modal-title' id='myModalLabel'>Delete Confirmation</h5>
                             <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                 <span aria-hidden='true'>&times;</span>
                             </button>
                         </div>
                         <div class='modal-body'>
                             <form method='POST' action='display_city.php'>
                                 <input type='hidden' name='DcityID' value='$cityID'>
                                 <h1> Are you sure you want to Delete this city? </h1>
                                 <button type='submit' name='archiveMe' class='btn btn-danger'>Delete</button>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>";
        }

        echo "</table>";

        // Pagination
        echo "<div class='pagination'>";
        echo "<ul class='pagination'>";
        if ($page > 1) {
            echo "<li class='page-item'><a class='page-link' href='?search=$search&entries=$entries&page=" . ($page - 1) . "'>&laquo;</a></li>";
        }
        for ($i = 1; $i <= $numPages; $i++) {
            $active = ($i == $page) ? 'active' : '';
            echo "<li class='page-item $active'><a class='page-link' href='?search=$search&entries=$entries&page=$i'>$i</a></li>";
        }
        if ($page < $numPages) {
            echo "<li class='page-item'><a class='page-link' href='?search=$search&entries=$entries&page=" . ($page + 1) . "'>&raquo;</a></li>";
        }
        echo "</ul>";
        echo "</div>";
    } else {
        echo "No records found.";
    }

    $conn->close();
    ?>
    <a class="btn btn-success" href="create_city.php" role="button">Add City</a>
</div>



</body>
</html>