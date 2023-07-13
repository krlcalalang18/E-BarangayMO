<?php
session_start();
// Include the Twilio PHP library
require_once 'C:/xampp/htdocs/testform/twilio-php-main/src/Twilio/autoload.php';

use Twilio\Rest\Client;

// Your Twilio account SID, auth token, and phone number
$accountSid = 'ACec827fed9a978732c8bf2c2fc1d56c89';
$authToken = '999481350b094444d665cad639f07c4c';
$twilioNumber = '+18142598459';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "ebarangaydatabase";

// Create connection
$connection = mysqli_connect("localhost", "root", "", "ebarangaydatabase");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if (isset($_POST['generateOTP'])) {
    // Get the recipient's phone number from the form
    $recipientNumber = $_POST['cellphoneNumber'];
    $intlNum = '+63';
    $phNum = '0';

    $finalNum = $intlNum . $recipientNumber;
    $searchNum = $phNum . $recipientNumber;

    

    // Generate and store the OTP
    $otp = mt_rand(100000, 999999);
    $sql = "UPDATE user SET otp = '$otp' WHERE cellphoneNumber = '$searchNum'";
    // Your custom message
    $message = "Your OTP is: $otp";

    // Create a Twilio client
    $client = new Client($accountSid, $authToken);
    $result = mysqli_query($connection, $sql);

    if ($result) {

        try {
             //Send the SMS message
            $message = $client->messages->create(
                $finalNum,
                [
                    'from' => $twilioNumber,
                    'body' => $message
                ]
            );
    
            // SMS sent successfully
            echo "OTP sent to $finalNum";
            $otpSent = true;
        } catch (Exception $e) {
            // Error in sending OTP


            
            $error = "Failed to send OTP. Please try again.";
        }
    } else {
        // Error in generating OTP
        $error = "Failed to generate OTP. Please try again.";
    }
}

// Check if the form is submitted with the OTP
if (isset($_POST['verify_otp'])) {
    $enteredOtp = $_POST['otp'];
    $recipientNumber = $_POST['cellphoneNumber'];
    $phNum = '0';
    $searchNum = $phNum . $recipientNumber;

   

    // Verify the entered OTP
    $sql = "SELECT userID, cellphoneNumber FROM user WHERE cellphoneNumber = '$searchNum' AND otp = '$enteredOtp'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = $result->fetch_assoc();
        // OTP is correct, allow password change
        $userID = $row['userID'];

        $_SESSION['passID'] = $row['userID'];
        header("Location: change_password_page.php");
    } else {
        // Incorrect OTP
        $error = "Invalid OTP. Please try again.";
        echo "your number is $searchNum , your otp is $otp , and your entered otp is $enteredOtp";
    }
}

// Close the database connection

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Forgot Password Citizen</h2>

        <!-- Forgot password form -->
        <?php if (!isset($otpSent)) { ?>
            <!-- HTML form to enter the phone number -->
<form method="POST">
    <label for="cellphoneNumber">Enter your phone number: +63</label>
    <input type="text" name="cellphoneNumber" id="cellphoneNumber"  required>
    <button type="submit" name="generateOTP" >Send OTP</button>
</form>

        <?php } ?>

        <!-- OTP verification form -->
        <?php if (isset($otpSent)) { ?>
            <form method="POST" class="mt-4">

            <div class="alert alert-primary" role="alert">
            An OTP has been sent to your Cellphone Number!
            </div>
                
                <div class="form-group">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" class="form-control" id="otp" name="otp" required>
                    <input type="hidden" name="cellphoneNumber" value="<?php  echo $_POST['cellphoneNumber']; ?>">
                </div>
                <button type="submit" name="verify_otp" class="btn btn-primary">Verify OTP</button>
            </form>
        <?php } ?>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php } ?>

        <br>
        <a href="citizen_login_page.html"><button type="submit"class="btn btn-info">Back to Login</button></a>
        <br>
        <br>
        <a href="change_password.php"><button type="submit"class="btn btn-danger">Verify through Email</button></a>
    </div>

    
</body>
</html>
