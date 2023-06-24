<!DOCTYPE html>
<html>
<head>
    <title>Barangay Stations</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Barangay Stations</h1>

        <?php
        $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT bs.barangayID, bs.barangayName, bs.barangayHallAddress, c.cityName
                FROM barangay_station bs
                INNER JOIN city c ON bs.cityID = c.cityID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead>
                    <tr>
                        <th>Barangay Name</th>
                        <th>Barangay Hall Address</th>
                        <th>City</th>
                    </tr>
                </thead>";

            while ($row = $result->fetch_assoc()) {
                echo "<tbody>";
                echo "<tr>";
                echo "<td>" . $row["barangayName"] . "</td>";
                echo "<td>" . $row["barangayHallAddress"] . "</td>";
                echo "<td>" . $row["cityName"] . "</td>";
                echo "</tr>";
                echo "</tbody>";
            }

            echo "</table>";
        } else {
            echo "<p>No barangay stations found.</p>";
        }

        $conn->close();
        ?>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
