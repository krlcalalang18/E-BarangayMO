<!DOCTYPE html>
<html>
<head>
    <title>Create Complaint</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Create Complaint</h2>
        <form method="POST" action="create_complaint.php">
            <div class="form-group">
                <label for="complaintType">Complaint Type:</label>
                <input type="text" class="form-control" id="complaintType" name="complaintType" required>
            </div>
            <div class="form-group">
                <label for="complaintAddress">Complaint Address:</label>
                <input type="text" class="form-control" id="complaintAddress" name="complaintAddress" required>
            </div>
            <div class="form-group">
                <label for="complaintDetails">Complaint Details:</label>
                <textarea class="form-control" id="complaintDetails" name="complaintDetails" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
