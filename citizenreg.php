<!DOCTYPE html>
<html>
<head>
    <title>Create Citizen Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Create Citizen Record</h1>
        <form method="POST" action="create_citizen.php">
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
                <input type="text" class="form-control" id="middleName" name="middleName" required>
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
                <select class="form-control" id="city" name="city" required>
                    <option value="Santa Rosa City">Santa Rosa City</option>
                </select>
            </div>
            <div class="form-group">
                <label for="barangay">Barangay:</label>
                <select class="form-control" id="barangay" name="barangay" required>
                    <option value="Balibago">Balibago</option>
                    <option value="Malitlit">Malitlit</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cellphoneNumber">Cellphone Number:</label>
                <input type="tel" class="form-control" id="cellphoneNumber" name="cellphoneNumber" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="accountType">Account Type:</label>
                <select class="form-control" id="accountType" name="accountType" required>
                    <option value="Citizen">Citizen</option>
                </select>
            </div>
            <input type="hidden" name="accountStatus" value="Pending">
            <button type="submit" class="btn btn-primary">Create Citizen</button>
        </form>
    </div>
</body>
</html>
