<?php
// -------------------------------------------------------------------
// AGRINOVA - MAILER CONFIGURATION
// Handles sending emails using PHPMailer + Gmail App Password
// -------------------------------------------------------------------

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/PHPMailer/src/Exception.php";
require __DIR__ . "/PHPMailer/src/PHPMailer.php";
require __DIR__ . "/PHPMailer/src/SMTP.php";

function sendMail($to, $subject, $bodyHtml) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "hasarangasamarakoon@gmail.com";      // your gmail
        $mail->Password   = "ombq cpeu vqmw bzot";      // gmail app password
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;

        // Send from
        $mail->setFrom("yourgmail@gmail.com", "AgriNova");

        // Receiver
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;

        return $mail->send();
    } 
    catch (Exception $e) {
        return false;
    }
}
