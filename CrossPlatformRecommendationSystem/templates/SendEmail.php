<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:/Users/elaku/vendor/autoload.php';


/*Using Outlook's SMTP because Gmail's SMTP is failing the authentication.
Outlook's SMTP does display the CSS properly, so using plaintext body message.
*/
function sendEmail($toAdr, $subject, $body) {
    $mail=new PHPMailer();
    // $mail->SMTPDebug=3; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host='smtp-mail.outlook.com'; // Specify main SMTP server
    $mail->SMTPAuth=True; // Enable SMTP authentication
    $mail->Username='crossplatformrs@gmail.com'; // SMTP username
    $mail->Password='Mi@mi@123$'; // SMTP password
    $mail->SMTPSecure='starttls'; // Enable TLS encryption, 'ssl' also accepted
    $mail->Port=587;  

    $mail->setFrom('crossplatformrs@gmail.com', 'Cross Platform Recommendation System'); // Set sender of the mail
    $mail->addAddress($toAdr); // Add a recipient
       
    $mail->Subject=$subject;
    $mail->Body=$body;

    if(!$mail->send()) {
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        //echo 'Message has been sent';
    }
}
?>