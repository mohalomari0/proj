<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // إعدادات SMTP لـ Mailtrap
    $mail->isSMTP();
    $mail->Host = 'smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Username = 'f7dcb27fc2046f'; // استبدل بمعرف Mailtrap الخاص بك
    $mail->Password = '5ba43f1a980ae4'; // استبدل بكلمة مرور Mailtrap الخاصة بك
    $mail->SMTPSecure = 'tls'; // أو 'ssl' إذا كنت تستخدم المنفذ 465
    $mail->Port = 2525; // أو 465 إذا كنت تستخدم SSL

    // إعدادات البريد
    $mail->setFrom('eelectrohomee@gmail.com', 'Test');
    $mail->addAddress('recipient@example.com'); // استبدل ببريد المستلم

    // محتوى البريد
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email.';

    // إرسال البريد
    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Mailer Error: " . $e->getMessage();
}
?>