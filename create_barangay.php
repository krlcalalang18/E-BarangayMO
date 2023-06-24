<!DOCTYPE html>
<html>
<head>
    <title>Add Barangay</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Add Barangay</h1>

        <?php
$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

$conn->close();
?>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="barangayName">Barangay Name:</label>
                <input type="text" class="form-control" id="barangayName" name="barangayName" required>
            </div>
            <div class="form-group">
                <label for="barangayHallAddress">Barangay Hall Address:</label>
                <input type="text" class="form-control" id="barangayHallAddress" name="barangayHallAddress" placeholder="Building Number, Street Name..." required>
            </div>
            <div class="form-group">
                <label for="cityID">City:</label>
                <select class="form-control" id="cityID" name="cityID" required>
                    <?php
                    $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT cityID, cityName FROM city";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["cityID"] . "'>" . $row["cityName"] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No cities found</option>";
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Record</button>
        </form>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
