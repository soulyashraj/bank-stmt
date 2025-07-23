<?php

require("class.phpmailer.php");

$mail = new PHPMailer();

$mail->IsSMTP();

$mail->Host = "rrtransport.in";

$mail->SMTPAuth = true;

//$mail->SMTPSecure = "ssl";

$mail->Port = 587;

$mail->Username = "info@rrtransport.in";

$mail->Password = "ranveerrao";

$mail->SMTPDebug = 2;

$mail->From = "info@rrtransport.in";

$mail->FromName = "RR Transport";

$mail->AddAddress("prathmeshkarekar11@gmail.com");

//$mail->AddReplyTo("mail@mail.com");



$mail->IsHTML(true);



$mail->Subject = "Test message from server";

$mail->Body = "Test Mail<b>in bold!</b>";

//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";



if(!$mail->Send())

{

echo "Message could not be sent. <p>";

echo "Mailer Error: " . $mail->ErrorInfo;

exit;

}



echo "Message has been sent";



?>