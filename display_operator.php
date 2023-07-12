<?php
session_start();

if (!isset($_SESSION['sessionAdminID'])){

    header("Location: session_error_page_admin.php");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Operators</title>
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
                <a href="display_barangay.php"><div class="tab">Barangays</div></a>
                <a href="display_operator.php"><div class="tab active">Barangay Operator Management</div></a>
                <a href="display_lgu_operator.php"><div class="tab">LGU Operator Management</div></a>
                <a href="adminBlockerPage.html"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>
        </div>
        <div class="content">

<h2>Operators</h2>

<?php
$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$entries = isset($_GET['entries']) ? intval($_GET['entries']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $entries;

$sqlCount = "SELECT COUNT(*) AS total
             FROM user
             INNER JOIN barangay_operator ON user.userID = barangay_operator.userID
             INNER JOIN barangay_station ON barangay_operator.barangayID = barangay_station.barangayID
             WHERE CONCAT(user.userID, user.firstName, user.lastName, user.middleName, user.emailAddress, user.cellphoneNumber, user.streetAddress, user.city, user.barangay, user.accountType, barangay_station.barangayName, user.accountStatus) LIKE '%$search%'";
$resultCount = $conn->query($sqlCount);
$row = $resultCount->fetch_assoc();
$total = $row['total'];
$numPages = ceil($total / $entries);

$sqlOptions = "SELECT barangayName, barangayID
               FROM barangay_station
               ORDER BY barangayName ASC";
$resultOptions = $conn->query($sqlOptions);

$options = '';

while ($rowOptions = mysqli_fetch_assoc($resultOptions)) {
    $DbarangayName = $rowOptions['barangayName'];
    $DbarangayID = $rowOptions["barangayID"];
    $options .= "<option value='$DbarangayID'>$DbarangayName</option>";
}

$sql = "SELECT user.userID, user.firstName, user.lastName, user.middleName, user.emailAddress, user.cellphoneNumber, user.streetAddress, user.city, user.barangay, user.accountType, barangay_station.barangayName AS barangayStation, user.accountStatus
        FROM user
        INNER JOIN barangay_operator ON user.userID = barangay_operator.userID
        INNER JOIN barangay_station ON barangay_operator.barangayID = barangay_station.barangayID
        WHERE CONCAT(user.userID, user.firstName, user.lastName, user.middleName, user.emailAddress, user.cellphoneNumber, user.streetAddress, user.city, user.barangay, user.accountType, barangay_station.barangayName, user.accountStatus) LIKE '%$search%'
        ORDER BY user.lastName ASC, user.firstName ASC
        LIMIT $entries OFFSET $offset";
$result = $conn->query($sql);

?>
<?php
// Search Bar and Show Entries
echo "<div class='row mb-3'>";
echo "<div class='col-md-6'>
        <form method='GET' action='display_operator.php' class='form-inline'>
            <input type='text' class='form-control mr-2' name='search' value='$search' placeholder='Search Operator'>
            <button type='submit' class='btn btn-primary'>Search</button>
        </form>
    </div>";
echo "<div class='col-md-6'>
        <form method='GET' action='display_operator.php' class='form-inline'>
            <label for='entries' class='mr-2'>Show entries:</label>
            <select class='form-control mr-2' id='entries' name='entries' onchange='this.form.submit()'>
                <option value='10' " . ($entries == 10 ? "selected" : "") . ">10</option>
                <option value='20' " . ($entries == 20 ? "selected" : "") . ">20</option>
                <option value='30' " . ($entries == 30 ? "selected" : "") . ">30</option>
            </select>
        </form>
    </div>";
echo "</div>";

// Operators Table
echo "<table class='table table-bordered'>";
echo "<thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Middle Name</th>
            <th>Email</th>
            <th>Cellphone Number</th>
            <th>Street Address</th>
            <th>City</th>
            <th>Barangay</th>
            <th>Account Type</th>
            <th>Barangay Station</th>
            <th>Account Status</th>
            <th>Action</th>
        </tr>
    </thead>";
echo "<tbody>";

while ($row = $result->fetch_assoc()) {
    $userID = $row['userID'];
    $firstName = $row['firstName'];
    $lastName = $row['lastName'];
    $middleName = $row['middleName'];
    $emailAddress = $row['emailAddress'];
    $cellphoneNumber = $row['cellphoneNumber'];
    $streetAddress = $row['streetAddress'];
    $city = $row['city'];
    $barangay = $row['barangay'];
    $accountType = $row['accountType'];
    $barangayStation = $row['barangayStation'];
    $accountStatus = $row['accountStatus'];

    echo "<tr>";
    echo "<td>$firstName</td>";
    echo "<td>$lastName</td>";
    echo "<td>$middleName</td>";
    echo "<td>$emailAddress</td>";
    echo "<td>$cellphoneNumber</td>";
    echo "<td>$streetAddress</td>";
    echo "<td>$city</td>";
    echo "<td>$barangay</td>";
    echo "<td>$accountType</td>";
    echo "<td>$barangayStation</td>";
    echo "<td>$accountStatus</td>";
    echo "<td>
            <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$userID'>
                Edit
            </button>
            <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteModal$userID'>
                Delete
            </button>
        </td>";
    echo "</tr>";

    //POP UP MODAL
    echo "<div class='modal fade' id='myModal$userID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
             <div class='modal-dialog' role='document'>
                 <div class='modal-content'>
                     <div class='modal-header'>
                         <h5 class='modal-title' id='myModalLabel'>Edit Operator</h5>
                         <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                             <span aria-hidden='true'>&times;</span>
                         </button>
                     </div>
                     <div class='modal-body'>
                         <form method='POST' action='display_operator.php'>
                             <input type='hidden' name='userID' value='$userID'>

                             <div class='form-group'>
                                 <label for='cityName'>First Name</label>
                                 <input type='text' class='form-control' value='$firstName' readonly>
                             </div>

                             <div class='form-group'>
                                 <label for='cityName'>Middle Name</label>
                                 <input type='text' class='form-control' value='$middleName' readonly>
                             </div>

                             <div class='form-group'>
                                 <label for='cityName'>Last Name</label>
                                 <input type='text' class='form-control' value='$lastName' readonly>
                             </div>

                             <div class='form-group'>
                                 <label for='cityName'>Email Address</label>
                                 <input type='text' class='form-control' value='$emailAddress' readonly>
                             </div>

                             <div class='form-group'>
                                 <label for='cityName'>Cellphone Number</label>
                                 <input type='text' class='form-control' value='$cellphoneNumber' readonly>
                             </div>

                             <div class='form-group'>
                                 <label for='cityName'>Street Address</label>
                                 <input type='text' class='form-control' value='$streetAddress' readonly>
                             </div>

                             <div class='form-group'>
                                 <label for='cityName'>City</label>
                                 <input type='text' class='form-control' value='$city' readonly>
                             </div>

                             <div class='form-group'>
                                 <label for='cityName'>Barangay</label>
                                 <input type='text' class='form-control' value='$barangay' readonly>
                             </div>

                             <div class='form-group'>
                                 <label for='cityName'>Account Type</label>
                                 <input type='text' class='form-control' value='$accountType' readonly>
                             </div>

                             <div class='form-group'>
                                 <label for='status'>Barangay Station</label>
                                 <select class='form-control' name='DbarangayID'>
                                     <option value='$barangayStation' selected disabled> $barangayStation </option>
                                     $options
                                 </select>
                             </div>

                             <div class='form-group'>
                                 <label for='status'>Account Status</label>
                                 <select class='form-control' name='accountStatus'>
                                     <option value='Active' " . ($accountStatus == 'Active' ? 'selected' : '') . ">Active</option>
                                     <option value='Inactive' " . ($accountStatus == 'Inactive' ? 'selected' : '') . ">Inactive</option>
                                 </select>
                             </div>

                             <button type='submit' class='btn btn-primary' name='updateMe'>Update</button>
                         </form>
                     </div>
                 </div>
             </div>
         </div>";

    //DELETE MODAL
    echo "<div class='modal fade' id='deleteModal$userID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
             <div class='modal-dialog' role='document'>
                 <div class='modal-content'>
                     <div class='modal-header'>
                         <h5 class='modal-title' id='myModalLabel'>Delete Confirmation</h5>
                         <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                             <span aria-hidden='true'>&times;</span>
                         </button>
                     </div>
                     <div class='modal-body'>
                         <form method='POST' action='display_operator.php'>
                             <input type='hidden' name='DuserID' value='$userID'>
                             <h1> Are you sure you want to delete this operator? </h1>
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

$conn->close()
?>
<a class="btn btn-success" href="create_operator.php" role="button">Add Operator</a>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</div>
</div>
</div>


</body>
</body>
</html>