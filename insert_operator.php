<!DOCTYPE html>
<html>
<head>
    <title>Insert Operator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <?php
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $middleName = $_POST['middleName'];
        $emailAddress = $_POST['emailAddress'];
        $cellphoneNumber = $_POST['cellphoneNumber'];
        $streetAddress = $_POST['streetAddress'];
        $city = $_POST['city'];
        $barangay = $_POST['barangay'];
        $accountType = $_POST['accountType'];
        $barangayStation = $_POST['barangayStation'];
        $password = $_POST['password'];
        $accountStatus = $_POST['accountStatus'];

        // Perform database insertion and validation
        // Insert record into the user table

        $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Insert into user table
        $sql = "INSERT INTO user (firstName, lastName, middleName, emailAddress, cellphoneNumber, streetAddress, city, barangay, accountType, password, accountStatus)
                VALUES ('$firstName', '$lastName', '$middleName', '$emailAddress', '$cellphoneNumber', '$streetAddress', '$city', '$barangay', '$accountType', '$password', '$accountStatus')";

        if ($conn->query($sql) === true) {
            $userID = $conn->insert_id;

            // Check account type and insert into corresponding table
            if ($accountType === 'Barangay Operator') {
                $sql = "INSERT INTO barangay_operator (userID, barangayID)
                        VALUES ('$userID', '$barangayStation')";
            } elseif ($accountType === 'LGU Operator') {
                $sql = "INSERT INTO lgu_operator (userID)
                        VALUES ('$userID')";
            }

            if ($conn->query($sql) === true) {
                header("Location: confirmation.php");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
