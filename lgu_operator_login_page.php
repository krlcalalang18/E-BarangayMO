<!DOCTYPE html>
<html>
<head>
    <title>LGU Operator Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>LGU Operator Login</h1>

        <form method="POST" action="lgu_operator_login.php">
            <div class="form-group">
                <label for="cellphoneNumber">Cellphone Number</label>
                <input type="text" class="form-control" id="cellphoneNumber" name="cellphoneNumber" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>

        </form>


        <br/>
        <a href="adminBlockerPage.html"> Administrator Login </a>
        <br/>
        <a href="index.php"> Barangay Operator Login </a>
    </div>
</body>
</html>
