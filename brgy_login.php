<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error!</title>
    <link rel="stylesheet" href="brgysessionerror.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" 
    crossorigin="anonymous">
    <style>
        .container {
            max-width: 100%;
            margin-right: 0px;
            margin-left: 0px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="popup">
            <img src="caution.png">
            <h2>Error!</h2>
            <p>Your Barangay login credentials are incorrect.</p>
            <?php
                session_start();
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');
                
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                
                $cellphoneNumber = $_POST['cellphoneNumber'];
                $password = $_POST['password'];
                
                $sql = "SELECT * FROM user WHERE cellphoneNumber = '$cellphoneNumber' AND password = '$password' AND accountType = 'Barangay Operator'";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                
                    //START SESSION HERE 
                    $_SESSION['sessionBrgyOperatorID'] = $row['userID'];
                    header("Location: admin.php");
                } else {
                    echo "<br><center><a class='btn btn-danger w-100' href='index.php'>Back to login</a></center><br>";
                }
                
                $conn->close();
            ?>
           
        </div>
    </div>

</body>
</html>


