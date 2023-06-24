<?php
$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql1 = "SELECT user.firstName, user.lastName, user.middleName, user.emailAddress, user.cellphoneNumber, user.streetAddress, user.city, user.barangay, user.accountType, barangay_station.barangayName AS barangayStation, user.accountStatus
         FROM user
         INNER JOIN barangay_operator ON user.userID = barangay_operator.userID
         INNER JOIN barangay_station ON barangay_operator.barangayID = barangay_station.barangayID";

$result1 = $conn->query($sql1);

$sql2 = "SELECT user.firstName, user.lastName, user.middleName, user.emailAddress, user.cellphoneNumber, user.streetAddress, user.city, user.barangay, user.accountType, '' AS barangayStation, user.accountStatus
         FROM user
         INNER JOIN lgu_operator ON user.userID = lgu_operator.userID";

$result2 = $conn->query($sql2);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Operators</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Operators</h2>
        <table class="table table-bordered">
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
                </tr>
            </thead>
            <tbody>
                <?php
                // BARANGAY OPERATORS
                if ($result1->num_rows > 0) {
                    while ($row = $result1->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['firstName']."</td>";
                        echo "<td>".$row['lastName']."</td>";
                        echo "<td>".$row['middleName']."</td>";
                        echo "<td>".$row['emailAddress']."</td>";
                        echo "<td>".$row['cellphoneNumber']."</td>";
                        echo "<td>".$row['streetAddress']."</td>";
                        echo "<td>".$row['city']."</td>";
                        echo "<td>".$row['barangay']."</td>";
                        echo "<td>".$row['accountType']."</td>";
                        echo "<td>".$row['barangayStation']."</td>";
                        echo "<td>".$row['accountStatus']."</td>";
                        echo "</tr>";
                    }
                }

                // LGU OPERATORS
                if ($result2->num_rows > 0) {
                    while ($row = $result2->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['firstName']."</td>";
                        echo "<td>".$row['lastName']."</td>";
                        echo "<td>".$row['middleName']."</td>";
                        echo "<td>".$row['emailAddress']."</td>";
                        echo "<td>".$row['cellphoneNumber']."</td>";
                        echo "<td>".$row['streetAddress']."</td>";
                        echo "<td>".$row['city']."</td>";
                        echo "<td>".$row['barangay']."</td>";
                        echo "<td>".$row['accountType']."</td>";
                        echo "<td>".$row['barangayStation']."</td>";
                        echo "<td>".$row['accountStatus']."</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
