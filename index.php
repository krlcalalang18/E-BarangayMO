<!DOCTYPE html>
<html>
<head>
    <title>Brgy Operator Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="brgyoperatorloginpage.css">
    <style>
        .container {
            margin-top: 100px;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <img src="Logo.png" class="logo">
    <div class="container">
        <h1>Barangay Operator Login</h1>

        <form method="POST" action="brgy_login.php">
            <div class="form-group">
                <label for="cellphoneNumber">Cellphone Number</label>
                <input type="text" class="form-control" id="cellphoneNumber" name="cellphoneNumber" required>

                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="text-center">
            <button type="submit" class="btn btn-primary w-100">Login</button>
            </div>

        </form>
        <br>
        <div class="link-a">
        <a href="adminBlockerPage.html"> Administrator</a>
        </div>
        <div class="link-b">
        <a href="lgu_operator_login_page.php"> LGU Operator</a>
        </div>
        <div class="reg-c">
        <a href="change_password_web.php"> Forgot Password?</a>
        </div>
    </div>
</body>
</html>
