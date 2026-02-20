<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust path if not using Composer or include PHPMailer manually

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = strip_tags(trim($_POST["full_name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST["phone"]));
    $language = strip_tags(trim($_POST["language"]));
    $interest = strip_tags(trim($_POST["interest"]));

    // Check required fields
    if (empty($fullName) || empty($email) || empty($phone) || empty($interest)) {
        header("Location: index.html?status=error&message=Missing required fields");
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            
        $mail->Host       = 'smtp.example.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'your_email@example.com';               // SMTP username
        $mail->Password   = 'your_password';                        // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('from@example.com', 'Belhasa Driving Center Landing Page');
        $mail->addAddress('recipient@example.com');     // Add a recipient

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'New Ramadan Offer Lead: ' . $fullName;
        $mail->Body    = "
            <h2>New Booking Request</h2>
            <p><strong>Name:</strong> {$fullName}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Language:</strong> {$language}</p>
            <p><strong>Interest:</strong> {$interest}</p>
        ";
        $mail->AltBody = "Name: {$fullName}\nEmail: {$email}\nPhone: {$phone}\nLanguage: {$language}\nInterest: {$interest}";

        $mail->send();
        header("Location: index.html?status=success");
    } catch (Exception $e) {
        header("Location: index.html?status=error&message=Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
} else {
    header("Location: index.html");
    exit;
}
?>
