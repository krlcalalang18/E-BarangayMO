<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error!</title>
    <link rel="stylesheet" href="lgusessionerror.css">

</head>
<body>
    <div class="container">
        <div class="popup">
            <img src="caution.png">
            <h2>Error!</h2>
            <p>Your LGU login credentials are incorrect.</p>
            <?php
                session_start();
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');
                
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                $cellphoneNumber = $_POST['cellphoneNumber'];
                $password = $_POST['password'];
                
                $sql = "SELECT * FROM user WHERE cellphoneNumber = '$cellphoneNumber' AND password = '$password' AND accountType = 'LGU Operator'";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $_SESSION['LGUOperatorID'] = $row['userID'];
                    header("Location: display_citizen.php");
                } else {
                    echo "<br><center><a class='btn' href='lgu_operator_login_page.php'>Back to login</a></center><br>";
                }
                
                $conn->close(); 
                ?>
           
        </div>
    </div>

</body>
</html>


