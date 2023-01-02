<?php
/** 
To send an email to the user's email.
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
# Add the path to the file autoload.php in the following line.
require 'path-to autoload.php';


/*Using Outlook's SMTP because Gmail's SMTP is failing the authentication.
Outlook's SMTP does display the CSS properly, so using plaintext body message.
*/
function sendEmail($toAdr, $subject, $body) {
    $mail=new PHPMailer();
    // $mail->SMTPDebug=3; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host='smtp-mail.outlook.com'; // Specify main SMTP server
    $mail->SMTPAuth=True; // Enable SMTP authentication
    
    # Enter your email and password to send emails to the user.
    $mail->Username='Enter your email'; // SMTP username
    $mail->Password='Enter your password'; // SMTP password
    $mail->SMTPSecure='starttls'; // Enable TLS encryption, 'ssl' also accepted
    $mail->Port=587;  

    # Enter your email as from-address to send emails.
    $mail->setFrom('Enter your email', 'Cross Platform Recommendation System'); // Set sender of the mail
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
