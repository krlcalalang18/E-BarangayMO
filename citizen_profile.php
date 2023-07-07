<?php
session_start();

if (!isset($_SESSION['citizenID'])){

    header("Location: citizen_session_error_page.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <style>
        @media (min-width: 576px) {
            .navbar-nav {
                margin-left: auto;
            }
        }

        .container {
            width: auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a href="citizen_home.php"><span class="navbar-brand">

        <?php 
                //GET SESSION DETAILS CONVERT TO NAME 
                $testSession = $_SESSION['citizenID'];
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if(isset($_POST['logout'])){
                    unset($_SESSION['citizenID']);
                    header("Location: citizen_login_page.html");
                }

                $sql = "SELECT firstName, lastName FROM user WHERE userID = '$testSession' AND accountType = 'Citizen'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $SfirstName = $row['firstName'];
                $SlastName = $row['lastName'];

                echo "$SfirstName $SlastName";
                } else {
                }   
                $conn->close();
                ?>

        </span></a>
        <ul class="navbar-nav">
            <li class="nav-item">
                <span class="navbar-text">Account Status: 

                <?php 
                $conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = " SELECT accountStatus 
                         FROM user 
                         WHERE userID = '$testSession'
                         AND accountType = 'Citizen'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $SaccountStatus = $row['accountStatus'];

                if($SaccountStatus == 'Active'){
                    echo "Verified";
                }

                else {
                    echo "Pending";
                } 
                
                }   
                $conn->close();
                ?>

                </span>
            </li>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item">
                <button class="btn btn-primary">Edit Profile</button>
            </li>
            &nbsp;
            <li class="nav-item">
                <form method="POST" action="citizen_profile.php">
                <button type="submit" name="logout" class="btn btn-danger">Log Out</button>
            </form>
            </li>
            </ul>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-3">Citizen Profile</h4>

                <?php
                // UPDATE OPERATOR PROFILE
                if (isset($_POST['updateProfile'])) {
                        $adminID = $_POST["adminID"];
                        $firstName = $_POST["firstName"];
                        $lastName = $_POST["lastName"];
                        $middleName = $_POST["middleName"];
                        $streetAddress = $_POST["streetAddress"];
                        $city = $_POST["city"];
                        $barangay = $_POST["barangay"];


                    $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "UPDATE user u
                            INNER JOIN citizen a ON u.userID = a.userID 
                            SET u.firstName = '$firstName', 
                                u.lastName = '$lastName',
                                u.middleName = '$middleName',
                                u.streetAddress = '$streetAddress',
                                u.city = '$city',
                                u.barangay = '$barangay'
                            WHERE a.userID = '$testSession'"; //ADD SESSION HERE
                    if ($conn->query($sql) === TRUE) {
                        header("Refresh:0");
                    } else {
                        echo "Error updating complaint: " . $conn->error;
                    }

                    $conn->close();
                    }

                    if (isset($_POST['updatePass'])) {

                    $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                    if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    }

                    $password = $_POST['password'];
                    $confirmPassword = $_POST['confirmPassword'];

                    if($password == $confirmPassword){
                        $sql = "UPDATE user u
                            INNER JOIN citizen a ON u.userID = a.userID 
                            SET u.password = '$password'
                            WHERE a.userID = '$testSession'";
                    if ($conn->query($sql) === TRUE) {
                        header("Refresh:0");
                    } else {
                    }
                    }
                    else {
                        echo " <h2> Password Error! </h2> ";
                    }

                    $conn->close();
                    }

                    if (isset($_POST['updateEmail'])) {

                        $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                        if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                        }
    
                        $email = $_POST['emailAddress'];
                        $confirmEmail = $_POST['confirmEmail'];
    
                        if($email == $confirmEmail){
                            $sql = "UPDATE user u
                                INNER JOIN citizen a ON u.userID = a.userID 
                                SET u.emailAddress = '$email'
                                WHERE a.userID = '$testSession'";
                        if ($conn->query($sql) === TRUE) {
                            header("Refresh:0");
                        } else {
                        }
                        }
                        else {
                            echo " <h2> Email Error! </h2> ";
                        }
    
                        $conn->close();
                        }

            ?>

                <?php
                $conn = new mysqli("localhost", "root", "", "ebarangaydatabase");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $testSession = $_SESSION['citizenID'];

                $sql = "SELECT c.citizenID, u.userID, u.firstName, u.lastName, u.middleName, u.emailAddress, u.streetAddress, u.city, u.barangay, u.cellphoneNumber, u.password, u.accountType, u.accountStatus
                        FROM user u
                        INNER JOIN citizen c ON u.userID = c.userID
                        WHERE c.userID ='$testSession'"; //THIS CODE SHOULD BE FROM THE SESSION
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $adminID = $row["citizenID"];
                        $userID = $row["userID"];
                        $firstName = $row["firstName"];
                        $lastName = $row["lastName"];
                        $middleName = $row["middleName"];
                        $emailAddress = $row["emailAddress"];
                        $streetAddress = $row["streetAddress"];
                        $city = $row["city"];
                        $barangay = $row["barangay"];
                        $cellphoneNumber = $row["cellphoneNumber"];
                        $password = $row["password"];
                        $accountType = $row["accountType"];
                        $accountStatus = $row["accountStatus"];

                        if ($accountStatus == "Active"){
                            $accountStatus = "Verified";
                        }
                        else {

                        }

                        //ADD DISPLAY HERE
                        echo "
                        
                        <div class='form-group'>
                        <label for='FirstName'>First Name</label>
                        <input type='text' class='form-control' value='$firstName' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Last Name</label>
                        <input type='text' class='form-control' value='$lastName' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Middle Name</label>
                        <input type='text' class='form-control' value='$middleName' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Email Address</label>
                        <input type='email' class='form-control' value='$emailAddress' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Street Address</label>
                        <input type='text' class='form-control' value='$streetAddress' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>City</label>
                        <input type='text' class='form-control' value='$city' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Barangay</label>
                        <input type='text' class='form-control' value='$barangay' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Cellphone Number</label>
                        <input type='text' class='form-control' value='$cellphoneNumber' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Password</label>
                        <input type='password' class='form-control' value='$password' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Account Type</label>
                        <input type='text' class='form-control' value='$accountType' readonly>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Account Status</label>
                        <input type='text' class='form-control' value='$accountStatus' readonly>
                        </div>

                        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#myModal$adminID'>
                                        Edit Profile
                        </button> 

                        <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#PmyModal$adminID'>
                                        Change Password
                        </button>

                        <button type='button' class='btn btn-warning' data-toggle='modal' data-target='#EmyModal$adminID'>
                                        Change Email Addess
                        </button>

                        <a href='citizen_resubmit_id.php'><button type='button' class='btn btn-info'>Resubmit ID  
                        </button></a>
                        <br> <br> <br>
                    
                        ";
                        //MODAL UP HERE

                        //POP UP MODAL
                        echo "<div class='modal fade' id='myModal$adminID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='myModalLabel'>Edit Profile</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>


                                        <form method='POST' action=''>
                                        <input type='hidden' name='adminID' value='$adminID'>

                        <div class='form-group'>
                        <label for='FirstName'>First Name</label>
                        <input type='text' class='form-control' value='$firstName' name='firstName'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Last Name</label>
                        <input type='text' class='form-control' value='$lastName' name='lastName'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Middle Name</label>
                        <input type='text' class='form-control' value='$middleName' name='middleName'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Street Address</label>
                        <input type='text' class='form-control' value='$streetAddress' name='streetAddress'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>City</label>
                        <input type='text' class='form-control' value='$city' name='city'>
                        </div>

                        <div class='form-group'>
                        <label for='LastName'>Barangay</label>
                        <input type='text' class='form-control' value='$barangay' name='barangay'>
                        </div>   

                        <button type='submit' name='updateProfile' class='btn btn-primary'>Update</button>
                        
                        </form>


                                        </div>
                                    </div>
                                </div>
                            </div>";

                            //POP UP MODAL UPDATE PASSWORD
                        echo "<div class='modal fade' id='PmyModal$adminID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='myModalLabel'>Change Password</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body'>


                                <form method='POST' action=''>
                                <input type='hidden' name='adminID' value='$adminID'>

                <div class='form-group'>
                <label for='LastName'>Password</label>
                <input type='password' class='form-control' placeholder='Password' name='password' required>
                </div>    

                <label for='LastName'>Confirm Password</label>
                <input type='password' class='form-control' placeholder='Confirm Password' name='confirmPassword' required>
                </div>     

                <button type='submit' name='updatePass' class='btn btn-primary'>Update</button>
                
                </form>


                                </div>
                            </div>
                        </div>
                    </div>";

                    //POP UP MODAL UPDATE Email
                    echo "<div class='modal fade' id='EmyModal$adminID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='myModalLabel'>Change Email</h5>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>


                            <form method='POST' action=''>
                            <input type='hidden' name='adminID' value='$adminID'>

            <div class='form-group'>
            <label for='LastName'>Email Address</label>
            <input type='email' class='form-control' placeholder='Enter New Email' name='emailAddress' required>
            </div>    

            <label for='LastName'>Confirm Email Address</label>
            <input type='email' class='form-control' placeholder='Confirm Email' name='confirmEmail' required>
            </div>     

            <button type='submit' name='updateEmail' class='btn btn-primary'>Update</button>
            
            </form>


                            </div>
                        </div>
                    </div>
                </div>";

                            
                    
                    }
                } else {
                    echo "<tr><td colspan='14'>Error Displaying</td></tr>";
                }

                
                $conn->close();
                ?>
                
            </div>
        </div>
    </div>

    

    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
