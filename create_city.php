<!DOCTYPE html>
<html>
<head>
    <title>Add City</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Add City</h1>
        <form method="POST" action="createCityRecord.php">
            <div class="form-group">
                <label for="cityName">City Name:</label>
                <input type="text" class="form-control" id="cityName" name="cityName" required>
            </div>
            <div class="form-group">
                <label for="cityExpiry">City Expiry:</label>
                <input type="date" class="form-control" id="cityExpiry" name="cityExpiry" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
