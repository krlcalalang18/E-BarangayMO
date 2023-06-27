<!DOCTYPE html>
<html>
<head>
    <title>Add Operator</title>
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
$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $middleName = $_POST['middleName'];
    $emailAddress = $_POST['emailAddress'];
    $streetAddress = $_POST['streetAddress'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $barangayStation = $_POST['barangayStation'];
    $cellphoneNumber = $_POST['cellphoneNumber'];
    $password = $_POST['password'];
    $accountType = $_POST['accountType'];
    $accountStatus = $_POST['accountStatus'];

    $sql = "INSERT INTO user (firstName, lastName, middleName, emailAddress, streetAddress, city, barangay, cellphoneNumber, password, accountType, accountStatus)
            VALUES ('$firstName', '$lastName', '$middleName', '$emailAddress', '$streetAddress', '$city', '$barangay', '$cellphoneNumber', '$password', '$accountType', '$accountStatus')";
    
    if ($conn->query($sql) === TRUE) {
        $lastInsertID = $conn->insert_id;
        
        if ($accountType === 'Barangay Operator') {
            $sql2 = "INSERT INTO barangay_operator (userID, barangayID)
                     VALUES ('$lastInsertID', '$barangayStation')";
            
            if ($conn->query($sql2) === TRUE) {
                header("Location: http://localhost/testform/display_operator.php");
                echo "Record inserted successfully.";
                
            } else {
                echo "Error inserting record: " . $conn->error;
            }
        }
        
        if ($accountType === 'LGU Operator') {
            $sql3 = "INSERT INTO lgu_operator (userID)
                     VALUES ('$lastInsertID')";
            
            if ($conn->query($sql3) === TRUE) {
                header("Location: http://localhost/testform/display_operator.php");
                echo "Record inserted successfully.";
                
            } else {
                echo "Error inserting record: " . $conn->error;
            }
        }
    } else {
        echo "Error inserting record: " . $conn->error;
    }

    $conn->close();
}
?>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile">
                <div class="profile-picture"></div>
                <div class="profile-name">James Russell Saro</div>
                <div class="profile-title">Administrator</div>
            </div>
            <div class="tabs">
                <a href="admin_profile.php"><div class="tab">Profile</div></a>
                <a href="display_city.php"><div class="tab">Cities</div></a>
                <a href="display_barangay.php"><div class="tab">Barangays</div></a>
                <a href="display_operator.php"><div class="tab active">Operator Management</div></a>
                <a href="index.php"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>
        </div>
        <div class="content">
        <h2>Add Operator</h2>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="form-group">
                <label for="middleName">Middle Name:</label>
                <input type="text" class="form-control" id="middleName" name="middleName">
            </div>
            <div class="form-group">
                <label for="emailAddress">Email Address:</label>
                <input type="email" class="form-control" id="emailAddress" name="emailAddress" required>
            </div>
            <div class="form-group">
                <label for="streetAddress">Street Address:</label>
                <input type="text" class="form-control" id="streetAddress" name="streetAddress" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="barangay">Barangay:</label>
                <input type="text" class="form-control" id="barangay" name="barangay" required>
            </div>
            <div class="form-group">
                <label for="barangayStation">Barangay Station:</label>
                <select class="form-control" id="barangayStation" name="barangayStation" required>
                    <option value="" disabled selected>Select Barangay Station</option>
                    <?php
                    $sql4 = "SELECT * FROM barangay_station";
                    $result4 = $conn->query($sql4);

                    if ($result4->num_rows > 0) {
                        while ($row4 = $result4->fetch_assoc()) {
                            echo "<option value='".$row4['barangayID']."'>".$row4['barangayName']."</option>";
                        }
                    }
                    ?>
                    <option value="">N/A</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cellphoneNumber">Cellphone Number:</label>
                <input type="text" class="form-control" id="cellphoneNumber" name="cellphoneNumber" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="accountType">Account Type:</label>
                <select class="form-control" id="accountType" name="accountType" required>
                    <option value="" disabled selected>Select Account Type</option>
                    <option value="Barangay Operator">Barangay Operator</option>
                    <option value="LGU Operator">LGU Operator</option>
                </select>
            </div>
            <div class="form-group">
                <label for="accountStatus">Account Status:</label>
                <select class="form-control" id="accountStatus" name="accountStatus" required>
                    <option value="" disabled selected>Select Account Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-success">Add Operator</button>
        </form>
    </div>
        </div>
    </div>

</body>
</body>
</html>