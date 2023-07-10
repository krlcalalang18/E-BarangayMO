<?php 
session_start();

if (!isset($_SESSION['passID'])){

    header("Location: change_password.php");
}

                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $testSession = $_SESSION['passID'];

                $sql = "SELECT emailAddress FROM user WHERE userID = '$testSession'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $SemailAddress = $row['emailAddress'];
                } else {
                }   

                if(isset($_POST['updatePass'])){

                    $newPass = $_POST['new_password'];
                    $confirmPass = $_POST['confirm_password'];

                    if ($newPass == $confirmPass){
                        $sqlUpdatePass = "UPDATE user SET password = '$newPass' WHERE userID = '$testSession'";
                        $resultUpdatePass = $conn->query($sqlUpdatePass);
                        unset($_SESSION['passID']);
                        header("Location: index.php");
                    }
                    else {
                        echo "Password Error!";
                    }
                              
                }

                if(isset($_POST['backToLogin'])){
                    unset($_SESSION['passID']);
                        header("Location: index.php");
                }

                
?>



<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Change Password Operator</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="new-password">New Password:</label>
                <input type="password" class="form-control" id="new-password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
            </div>
            <button type="submit" name="updatePass" class="btn btn-primary">Submit</button>
            <br> <br>
            <button type="submit" name="backToLogin" class="btn btn-info">Back to Login</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
