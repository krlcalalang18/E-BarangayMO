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
</head>
<body>
    <div class="container">
        <h1>Administrator Login</h1>

        <form method="POST" action="admin_login.php">
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
        <br>
        
        <a href="create_admin.php"> Register Here </a>
        <br/>
        <a href="index.php"> Barangay Operator Login </a>
        <br/>
        <a href="lgu_operator_login_page.php"> LGU Operator Login </a>
    </div>
</body>
</html>
