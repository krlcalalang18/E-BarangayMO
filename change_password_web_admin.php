<?php
session_start();

// Include PHPMailer library
require 'C:/xampp/htdocs/testform/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/testform/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require 'C:/xampp/htdocs/testform/PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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

// Check if the form is submitted to send OTP
if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];

    // Generate and store the OTP
    $otp = mt_rand(100000, 999999);
    $sql = "UPDATE user SET otp = '$otp' WHERE emailAddress = '$email'";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        // OTP generated and stored, send it to the user's email
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ebarangayhelp@gmail.com'; // Your email address
        $mail->Password = 'dcweytfqyvjnbkas'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        

        // Sender and recipient settings
        $mail->setFrom('EbarangayHelp@gmail.com', 'ebarangay_emailer'); // Your name and email address
        $mail->addAddress($email); // Recipient's email address


            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP';
            $mail->Body = "Your OTP for password reset is: " . $otp;

            // Send the email
            $mail->send();

            // OTP sent successfully
            $otpSent = true;
        } catch (Exception $e) {
            // Error in sending OTP
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->Debugoutput = function ($str, $level) {
    echo "Debug level $level; message: $str\n";
};

            
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
    $email = $_POST['email'];

    // Verify the entered OTP
    $sql = "SELECT userID, emailAddress FROM user WHERE emailAddress = '$email' AND otp = '$enteredOtp'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = $result->fetch_assoc();
        // OTP is correct, allow password change
        $userID = $row['userID'];

        $_SESSION['passID'] = $row['userID'];
        header("Location: change_password_page_web.php");
    } else {
        // Incorrect OTP
        $error = "Invalid OTP. Please try again.";
    }
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="changepasswebadmin.css">
    <style>
        .container {
            margin-top: 100px;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="text-center">
        <img src="Logo.png" class="img-fluid col-md-12" alt="logo">
    </div>
        <h2>Forgot Password Operator</h2>

        <!-- Forgot password form -->
        <?php if (!isset($otpSent)) { ?>
            <form method="POST" class="mt-4">
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="button">
                <button type="submit" name="send_otp" class="btn btn-primary">Send OTP</button>
                </div>
            </form>
        <?php } ?>

        <!-- OTP verification form -->
        <?php if (isset($otpSent)) { ?>
            <form method="POST" class="mt-4">
                <div class="form-group">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" class="form-control" id="otp" name="otp" required>
                    <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                </div>
                <button type="submit" name="verify_otp" class="btn btn-primary">Verify OTP</button>
            </form>
        <?php } ?>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger mt-4"><?php echo $error; ?></div>
        <?php } ?>
<br>
    <div class="button2">
        <a href="adminBlockerPage.php"><button type="submit"class="btn btn-info">Back to Login</button></a>
        <a href="change_password_web_sms_admin.php"><button type="submit"class="btn btn-danger">Verify through SMS</button></a>
    </div>
<br>
<br>

    </div>
</body>
</html>
