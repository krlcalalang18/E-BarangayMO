<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <h1>User Registration</h1>
        <form action="registration.php" method="POST" enctype="multipart/form-data">
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
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="barangay">Barangay:</label>
                <input type="text" class="form-control" id="barangay" name="barangay" required>
            </div>
            <div class="form-group">
                <label for="cellphoneNumber">Cellphone Number:</label>
                <input type="text" class="form-control" id="cellphoneNumber" name="cellphoneNumber" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="accountType"></label>
                <input type="hidden" class="form-control" id="accountType" name="accountType" value="Citizen">
            </div>
            <div class="form-group">
                <label for="accountStatus"></label>
                <input type="hidden" class="form-control" id="accountStatus" name="accountStatus" value="Pending">
            </div>
            <div class="form-group">
                <label for="idPicture">ID Picture:</label>
                <input type="file" class="form-control-file" id="idPicture" name="idPicture" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="idSelfPhoto">ID Self Photo:</label>
                <input type="file" class="form-control-file" id="idSelfPhoto" name="idSelfPhoto" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="idNumber">ID Number:</label>
                <input type="text" class="form-control" id="idNumber" name="idNumber" required>
            </div>
            <div class="form-group">
                <label for="idType">ID Type:</label>
                <input type="text" class="form-control" id="idType" name="idType" required>
            </div>
            <div class="form-group">
                <label for="idExpiry">ID Expiry:</label>
                <input type="date" class="form-control" id="idExpiry" name="idExpiry" required>
            </div>
            <div class="form-group">
                <label for="idBirthday">ID Birthday:</label>
                <input type="date" class="form-control" id="idBirthday" name="idBirthday" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <br>
        <a href="citizen_login_page.html"> Back to Login </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
