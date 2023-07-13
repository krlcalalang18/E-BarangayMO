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

    <!-- Search Bar -->
    <form method="GET" action="display_operator.php">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search operators" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </form>

    <?php
$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['updateMe'])) {
    $userID = $_POST["userID"];
    $barangayID = $_POST["DbarangayID"];
    $accountStatus = $_POST["accountStatus"];

    $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE user u
    INNER JOIN barangay_operator bo ON u.userID = bo.userID 
    SET
    u.accountStatus = '$accountStatus',
    bo.barangayID = '$barangayID'
    WHERE u.userID = '$userID'";
    if ($conn->query($sql) === TRUE) {

    } else {

        echo "Error updating Operator.";
    }
    $conn->close();
    }

    // delete
    if(isset($_POST['archiveMe'])){

        $DuserID = $_POST["DuserID"];

        $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $sql2 = "DELETE FROM user
                 WHERE userID = $DuserID";
        if ($conn->query($sql2) === TRUE) {

        } else {
            echo "Error deleting City.";
        }
        $conn->close();
    }

    $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT barangayName, barangayID
        FROM barangay_station
        ORDER BY barangayName ASC";
$result = mysqli_query($conn, $sql);

$options = '';
while ($row = mysqli_fetch_assoc($result)) {
    $DbarangayName = $row['barangayName'];
    $DbarangayID = $row["barangayID"];
    $options .= "<option value='$DbarangayID'>$DbarangayName</option>";
}

    // Define available options for number of entries per page
    $entries_per_page_options = array(10, 25, 50, 100);

    // Get the selected number of entries per page (default: 10)
    $entries_per_page = isset($_GET['entries']) && in_array($_GET['entries'], $entries_per_page_options)
        ? $_GET['entries']
        : $entries_per_page_options[0];

    // Pagination
    $results_per_page = $entries_per_page;
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start_index = ($current_page - 1) * $results_per_page;

    // Search Query
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $search_query = " AND (
        user.firstName LIKE '%$search%' OR
        user.lastName LIKE '%$search%' OR
        user.middleName LIKE '%$search%' OR
        user.emailAddress LIKE '%$search%' OR
        user.cellphoneNumber LIKE '%$search%' OR
        user.streetAddress LIKE '%$search%' OR
        user.city LIKE '%$search%' OR
        user.barangay LIKE '%$search%' OR
        user.accountType LIKE '%$search%' OR
        barangay_station.barangayName LIKE '%$search%' OR
        user.accountStatus LIKE '%$search%'
    )";

    $sql_count = "SELECT COUNT(*) AS total
        FROM user
        INNER JOIN barangay_operator ON user.userID = barangay_operator.userID
        INNER JOIN barangay_station ON barangay_operator.barangayID = barangay_station.barangayID
        WHERE 1" . $search_query;

    $result_count = $conn->query($sql_count);
    $total_results = $result_count->fetch_assoc()['total'];
    $total_pages = ceil($total_results / $results_per_page);

    $sql = "SELECT user.userID, user.firstName, user.lastName, user.middleName, user.emailAddress, user.cellphoneNumber, user.streetAddress, user.city, user.barangay, user.accountType, barangay_station.barangayName AS barangayStation, user.accountStatus, barangay_station.barangayID
        FROM user
        INNER JOIN barangay_operator ON user.userID = barangay_operator.userID
        INNER JOIN barangay_station ON barangay_operator.barangayID = barangay_station.barangayID
        WHERE 1" . $search_query . "
        ORDER BY user.userID
        LIMIT $start_index, $results_per_page";


    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered">
                <thead>
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
                </thead>
                <tbody>';

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
            $AbarangayID = $row['barangayID'];

            echo "<tr>
                    <td>".$row['firstName']."</td>
                    <td>".$row['lastName']."</td>
                    <td>".$row['middleName']."</td>
                    <td>".$row['emailAddress']."</td>
                    <td>".$row['cellphoneNumber']."</td>
                    <td>".$row['streetAddress']."</td>
                    <td>".$row['city']."</td>
                    <td>".$row['barangay']."</td>
                    <td>".$row['accountType']."</td>
                    <td>".$row['barangayStation']."</td>
                    <td>".$row['accountStatus']."</td>
                    <td>
                        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$userID'>Edit</button>
                        <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#deleteModal$userID'>Delete</button>
                    </td>
                </tr>";

            // Update Operator Modal
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
                                <form method='POST' action='display_operator.php?page=$current_page&search=$search'>
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
                                            <option value='$AbarangayID' selected hidden> $barangayStation </option>
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

            // Delete Confirmation Modal
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
                                <form method='POST' action='display_operator.php?page=$current_page&search=$search'>
                                    <input type='hidden' name='DuserID' value='$userID'>
                                    <h1>Are you sure you want to delete this operator?</h1>
                                    <button type='submit' name='archiveMe' class='btn btn-danger'>Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>";
        }

        echo '</tbody></table>';

        // Pagination Links
        echo '<nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">';

        if ($current_page > 1) {
            echo '<li class="page-item"><a class="page-link" href="display_operator.php?page=' . ($current_page - 1) . '&search=' . $search . '&entries=' . $entries_per_page . '">Previous</a></li>';
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<li class="page-item ' . ($current_page == $i ? 'active' : '') . '"><a class="page-link" href="display_operator.php?page=' . $i . '&search=' . $search . '&entries=' . $entries_per_page . '">' . $i . '</a></li>';
        }

        if ($current_page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="display_operator.php?page=' . ($current_page + 1) . '&search=' . $search . '&entries=' . $entries_per_page . '">Next</a></li>';
        }

        echo '</ul></nav>';
    } else {
        echo '<p>No operators found.</p>';
    }

    $conn->close();
    ?>

    <div class="form-group">
        <label for="entries">Show Entries:</label>
        <select class="form-control" id="entries" name="entries" onchange="this.form.submit()">
            <?php
            foreach ($entries_per_page_options as $option) {
                echo '<option value="' . $option . '"' . ($entries_per_page == $option ? ' selected' : '') . '>' . $option . '</option>';
            }
            ?>
        </select>
    </div>

    <a class="btn btn-success" href="create_operator.php" role="button">Add Operator</a>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</div>

</body>
</body>
</html>