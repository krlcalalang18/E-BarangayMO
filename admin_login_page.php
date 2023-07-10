<?php 
session_start();

if(isset($_SESSION['loggedin']) == true){

}
else {
    header("Location: session_error_page_admin_blocker.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrator Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="adminlogin2.css">
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
        <h1>Administrator Login</h1>

        <form method="POST" action="admin_login.php">
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
        <a href="create_admin.php"> Register Here </a>
        </div>
        <div class="link-b">
        <a href="index.php"> Barangay Operator </a>
        </div>
        <div class="link-c">
        <a href="lgu_operator_login_page.php"> LGU Operator</a>
        </div>
    </div>
</body>
</html>
