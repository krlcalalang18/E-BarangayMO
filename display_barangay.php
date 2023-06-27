<!DOCTYPE html>
<html>
<head>
    <title>Barangays</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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

<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile">
                <div class="profile-picture"></div>
                <div class="profile-name">James Russell Saro</div>
                <div class="profile-title">Administrator</div>
            </div>
            <div class="tabs">
                <a href=""><div class="tab">Profile</div></a>
                <a href="display_city.php"><div class="tab">Cities</div></a>
                <a href="display_barangay.php"><div class="tab">Barangays</div></a>
                <a href="display_operator.php"><div class="tab">Operator Management</div></a>
                <a href="index.php"><div class="tab logout">Log Out</div></a> <!--add logout codes here -->
            </div>
        </div>

        
        <div class="content">
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
    echo "<table class='table table-bordered'>";
    echo "<thead>
            <tr>
                <th>Barangay Name</th>
                <th>Barangay Hall Address</th>
                <th>City</th>
                <th>Action</th>
            </tr>
        </thead>";

    while ($row = $result->fetch_assoc()) {
        echo "<tbody>";
        echo "<tr>";
        echo "<td>" . $row["barangayName"] . "</td>";
        echo "<td>" . $row["barangayHallAddress"] . "</td>";
        echo "<td>" . $row["cityName"] . "</td>";
        echo "<td>
                <button type='button' class='btn btn-primary'>
                    Edit
                </button>
                <button type='button' class='btn btn-danger'>
                    Delete
                </button>
            </td>";
        echo "</tr>";
        echo "</tbody>";
    }

    echo "</table>";
} else {
    echo "<p>No barangay stations found.</p>";
}

$conn->close();
?>
<a class="btn btn-success" href="create_barangay.php" role="button">Add Barangay</a>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

                </div>
        </div>
    </div>

</body>
</body>
</html>