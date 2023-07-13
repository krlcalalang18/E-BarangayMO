<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="create_admin.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="text-center">
            <img src="Logo.png" class="img-fluid col-sm-3" alt="logo">
        </div>
        <h1>Admin Registration</h1>
        <form method="POST" action="insert_admin.php">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="form-group">
                <label for="middleName">Middle Name</label>
                <input type="text" class="form-control" id="middleName" name="middleName" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="streetAddress">Street Address</label>
                <input type="text" class="form-control" id="streetAddress" name="streetAddress" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="barangay">Barangay</label>
                <input type="text" class="form-control" id="barangay" name="barangay" required>
            </div>
            <div class="form-group">
                <label for="cellphoneNumber">Cellphone Number</label>
                <input type="text" class="form-control" id="cellphoneNumber" name="cellphoneNumber" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="accountStatus">Account Status:</label>
                <select class="form-control" id="accountStatus" name="accountStatus" required>
                    <option value="" disabled selected>Select Account Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a class="btn btn-danger" href="admin_login_page.php" role="button">Back</a>
        </form>
    </div>
</body>
</html>
