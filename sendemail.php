<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require_once 'vendor/autoload.php';
 
$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';  //gmail SMTP server
$mail->SMTPAuth = true;
//to view proper logging details for success and error messages
// $mail->SMTPDebug = 1;
$mail->Host = 'smtp.gmail.com';  //gmail SMTP server
$mail->Username = 'email@gmail.com';   //email
$mail->Password ='djsdjjhsdjhsdhj' ;   //16 character obtained from app password created
$mail->Port = 465;                    //SMTP port
$mail->SMTPSecure = "ssl";

//sender information
$mail->setFrom('FROM_EMAIL_ADDRESS', 'FROM_NAME');

//receiver email address and name
$mail->addAddress('RECEPIENT_EMAIL_ADDRESS', 'RECEPIENT_NAME'); 

// Add cc or bcc   
// $mail->addCC('email@mail.com');  
// $mail->addBCC('user@mail.com');  
 
 
$mail->isHTML(true);
 
$mail->Subject = 'PHPMailer SMTP test';
$mail->Body    = "<h4> PHPMailer the awesome Package </h4>
<b>PHPMailer is working fine for sending mail</b>
    <p> This is a tutorial to guide you on PHPMailer integration</p>";

// Send mail   
if (!$mail->send()) {
    echo 'Email not sent an error was encountered: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent.';
}

$mail->smtpClose();

?>
