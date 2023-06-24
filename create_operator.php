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

<!DOCTYPE html>
<html>
<head>
    <title>Add Operator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
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
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
