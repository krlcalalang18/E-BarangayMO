<?php
// Include the Twilio PHP library
require_once 'C:/xampp/htdocs/testform/twilio-php-main/src/Twilio/autoload.php';

use Twilio\Rest\Client;

// Your Twilio account SID, auth token, and phone number
$accountSid = 'ACec827fed9a978732c8bf2c2fc1d56c89';
$authToken = '999481350b094444d665cad639f07c4c';
$twilioNumber = '+18142598459';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the recipient's phone number from the form
    $recipientNumber = $_POST['phone_number'];

    // Generate OTP
    $otp = mt_rand(100000, 999999);

    // Your custom message
    $message = "Your OTP is: $otp";

    // Create a Twilio client
    $client = new Client($accountSid, $authToken);

    try {
        // Send the SMS message
        $message = $client->messages->create(
            $recipientNumber,
            [
                'from' => $twilioNumber,
                'body' => $message
            ]
        );

        // SMS sent successfully
        echo "OTP sent to $recipientNumber";
    } catch (Exception $e) {
        // Error sending SMS
        echo "Failed to send OTP. Error: " . $e->getMessage();
    }
}
?>

<!-- HTML form to enter the phone number -->
<form method="POST">
    <label for="phone_number">Enter your phone number:</label>
    <input type="text" name="phone_number" id="phone_number" required>
    <button type="submit">Send OTP</button>
</form>
