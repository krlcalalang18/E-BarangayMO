<?php
session_start();

if (!isset($_SESSION['sessionAdminID'])){

    header("Location: session_error_page_admin.php");
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Barangays</title>
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
            height: 134%;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #fff;
            height: 134%;
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

$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['updateMe'])) {
    $cityID = $_POST["cityID"];
    $barangayID = $_POST["barangayID"];
    $barangayName = $_POST["barangayName"];
    $barangayHallAddress = $_POST["barangayHallAddress"];


    $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE barangay_station
            SET barangayName = '$barangayName', barangayHallAddress = '$barangayHallAddress', cityID = '$cityID'
            WHERE barangayID = $barangayID";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error updating city.";
    }
    $conn->close();
    }

    if(isset($_POST['archiveMe'])){

        $barangayID = $_POST["barangayID"];

        $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $sql2 = "DELETE FROM barangay_station
                 WHERE barangayID = $barangayID";
        if ($conn->query($sql2) === TRUE) {
        } else {
            echo "Error deleting City.";
        }
        $conn->close();
    }


if (isset($_POST['createBarangay'])) {
    $barangayName = $_POST["barangayName"];
    $barangayHallAddress = $_POST["barangayHallAddress"];
    $cityID = $_POST["cityID"];

    $stmt = $conn->prepare("INSERT INTO barangay_station (barangayName, barangayHallAddress, cityID) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $barangayName, $barangayHallAddress, $cityID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Barangay created successfully.";
    } else {
        echo "Error creating barangay: " . $conn->error;
    }

    $stmt->close();
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
                <a href="display_city.php"><div class="tab">Cities</div></a>
                <a href="display_barangay.php"><div class="tab active">Barangays</div></a>
                <a href="display_operator.php"><div class="tab">Barangay Operator Management</div></a>
                <a href="display_lgu_operator.php"><div class="tab">LGU Operator Management</div></a>
                <a href="adminBlockerPage.html"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>
        </div>

        
    <div class="content">
    <h1>Barangay Stations</h1>
    

    <?php
    $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $entries = isset($_GET['entries']) ? intval($_GET['entries']) : 10;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $entries;

    $search = isset($_GET['search']) ? $_GET['search'] : '';
$entries = isset($_GET['entries']) ? intval($_GET['entries']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $entries;

$sqlCount = "SELECT COUNT(*) AS total
             FROM barangay_station bs
             INNER JOIN city c ON bs.cityID = c.cityID
             WHERE bs.barangayName LIKE '%$search%'
             OR bs.barangayHallAddress LIKE '%$search%'
             OR c.cityName LIKE '%$search%'";
$resultCount = $conn->query($sqlCount);
$row = $resultCount->fetch_assoc();
$total = $row['total'];
$numPages = ceil($total / $entries);

$sqlCity = "SELECT cityName, cityID FROM city";
$resultCity = $conn->query($sqlCity);

$options = '';
while ($rowCity = mysqli_fetch_assoc($resultCity)) {
    $cityName = $rowCity['cityName'];
    $cityID = $rowCity['cityID'];
    $options .= "<option value='$cityID'>$cityName</option>";
}

$sql = "SELECT bs.barangayID, bs.barangayName, bs.barangayHallAddress, c.cityName, c.cityID
        FROM barangay_station bs
        INNER JOIN city c ON bs.cityID = c.cityID
        WHERE bs.barangayName LIKE '%$search%'
        OR bs.barangayHallAddress LIKE '%$search%'
        OR c.cityName LIKE '%$search%'
        ORDER BY bs.barangayName ASC, c.cityName
        LIMIT $entries OFFSET $offset";
$result = $conn->query($sql);


    if ($result->num_rows > 0) {
        echo "<div class='row'>";
        echo "<div class='col-md-6 mb-3'>
                <form method='GET' action='display_barangay.php' class='form-inline'>
                    <input type='text' class='form-control mr-2' name='search' value='$search' placeholder='Search Barangay'>
                    <button type='submit' class='btn btn-primary'>Search</button>
                </form>
              </div>";
        echo "<div class='col-md-6 mb-3'>
                <form method='GET' action='display_barangay.php' class='form-inline'>
                    <label for='entries' class='mr-2'>Show entries:</label>
                    <select class='form-control mr-2' id='entries' name='entries' onchange='this.form.submit()'>
                        <option value='10' " . ($entries == 10 ? "selected" : "") . ">10</option>
                        <option value='20' " . ($entries == 20 ? "selected" : "") . ">20</option>
                        <option value='30' " . ($entries == 30 ? "selected" : "") . ">30</option>
                    </select>
                </form>
              </div>";
        echo "</div>";

        echo "<table class='table table-bordered'>";
        echo "<thead>
                <tr>
                    <th>Barangay Name</th>
                    <th>Barangay Hall Address</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
            $barangayID = $row["barangayID"];
            $barangayName = $row["barangayName"];
            $barangayHallAddress = $row["barangayHallAddress"];
            $cityID = $row["cityID"];
            $cityName = $row["cityName"];

            echo "<tr>";
            echo "<td>$barangayName</td>";
            echo "<td>$barangayHallAddress</td>";
            echo "<td>$cityName</td>";
            echo "<td>
                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$barangayID'>
                        Edit
                    </button>
                    <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteModal$barangayID'>
                        Delete
                    </button>
                </td>";
            echo "</tr>";

            //POP UP MODAL
            echo "<div class='modal fade' id='myModal$barangayID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                 <div class='modal-dialog' role='document'>
                     <div class='modal-content'>
                         <div class='modal-header'>
                             <h5 class='modal-title' id='myModalLabel'>Complaint Details</h5>
                             <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                 <span aria-hidden='true'>&times;</span>
                             </button>
                         </div>
                         <div class='modal-body'>
                             <form method='POST' action='display_barangay.php'>
                                 <input type='hidden' name='barangayID' value='$barangayID'>

                                 <div class='form-group'>
                                     <label for='barangayName'>Barangay Name</label>
                                     <input type='text' class='form-control' value='$barangayName' name='barangayName'>
                                 </div>

                                 <div class='form-group'>
                                     <label for='barangayHallAddress'>Barangay Hall Address</label>
                                     <input type='text' class='form-control' value='$barangayHallAddress' name='barangayHallAddress'>
                                 </div>

                                 <div class='form-group'>
                                     <label for='cityID'>City</label>
                                     <select class='form-control' name='cityID'>
                                         $options
                                     </select>
                                 </div>

                                 <button type='submit' class='btn btn-primary' name='updateMe'>Update</button>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>";

            //DELETE MODAL
            echo "<div class='modal fade' id='deleteModal$barangayID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                 <div class='modal-dialog' role='document'>
                     <div class='modal-content'>
                         <div class='modal-header'>
                             <h5 class='modal-title' id='myModalLabel'>Delete Confirmation</h5>
                             <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                 <span aria-hidden='true'>&times;</span>
                             </button>
                         </div>
                         <div class='modal-body'>
                             <form method='POST' action='display_barangay.php'>
                                 <input type='hidden' name='barangayID' value='$barangayID'>
                                 <h1>Are you sure you want to delete this city?</h1>
                                 <button type='submit' name='archiveMe' class='btn btn-danger'>Delete</button>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>";
        }

        echo "</tbody>";
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
        echo "<p>No barangay stations found.</p>";
    }

    $conn->close();
    ?>
    <a class="btn btn-success" href="create_barangay.php" role="button">Add Barangay</a>
    
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>




</body>
</body>
</html>