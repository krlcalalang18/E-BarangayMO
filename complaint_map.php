<?php
// MySQL database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ebarangaydatabase";

// Check if the latitude, longitude, and address values are received from the form
if (isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['address'])) {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $address = $_POST['address'];

    // Create a new MySQLi object and establish the database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement to insert the location data into the table
    $sql = "INSERT INTO testLoc (address, latitude, longitude) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdd", $address, $latitude, $longitude);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Location saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Select Location</title>
    <!-- Add the Google Maps API library -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYtCKKHW2orUxnry0Vyht44abg2YeGjIU&libraries=places"></script>
    <script>
        var map;
        var marker;

        function initialize() {
            var mapOptions = {
                zoom: 12,
                center: new google.maps.LatLng(0, 0) // Set initial map center
            };
            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

            // Add a marker when user clicks on the map
            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            });
        }

        function placeMarker(location) {
            // Remove previous marker, if exists
            if (marker) {
                marker.setMap(null);
            }

            // Add new marker to the clicked location
            marker = new google.maps.Marker({
                position: 
                map: map
            });

            // Update the latitude and longitude input fields
            document.getElementById('latitude').value = location.lat();
            document.getElementById('longitude').value = location.lng();
        }

        function saveLocation() {
            // Get the address, latitude, and longitude values from the form
            var address = document.getElementById('address').value;
            var latitude = parseFloat(document.getElementById('latitude').value);
            var longitude = parseFloat(document.getElementById('longitude').value);

            // Send the location data to the server using AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_location.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Display the server response
                    document.getElementById('response').innerHTML = xhr.responseText;
                }
            };
            xhr.send('address=' + address + '&latitude=' + latitude + '&longitude=' + longitude);
        }

        // Initialize the Google Maps and set up event listeners
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <style>
        #map-canvas {
            height: 400px;
            width: 60%;
            align-content: center;
        }
    </style>
</head>
<body>
    <h1>Select Location</h1>
    <div>
        <div id="map-canvas"></div>
        <div>
            Address: <input type="text" id="address"><br>
            Latitude: <input type="text" id="latitude" readonly><br>
            Longitude: <input type="text" id="longitude" readonly><br>
            <button onclick="saveLocation()">Save</button>
        </div>
    </div>
    <div id="response"></div>
</body>
</html>
