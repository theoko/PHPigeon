<?php

function send_mail($from, $email, $message, $subject = "COMPANY NAME")
{
 include_once "mailer/PHPMailerAutoload.php";
 $mail = new PHPMailer;
 $mail->isSMTP();
 // $mail->SMTPDebug = 0;
 $mail->Host = "smtp.gmail.com";
 $mail->SMTPAuth = true;
 $mail->Username = $from;
 $mail->Password = "email_password";
 $mail->SMTPSecure = 'ssl';
 $mail->Port = 465;
 $mail->From = $from;
 $mail->FromName = 'COMPANY NAME';
 $mail->addAddress($email);
 $mail->isHTML(true);
 $mail->Subject = $subject;
 $mail->Body = $message;
 $mail->send();
}

try {
  $host = "localhost";
  $dbname = "users";
  $username = "root";
  $password = "password";

  $dbc = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
  echo $e->getMessage();
}

$stmt = $dbc->query("SELECT * FROM send");
$stmt->execute();

if ($stmt->rowCount() > 0) {
  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  $iterator = new IteratorIterator($stmt);

  foreach ($iterator as $data) {
    echo "SENDING TO: ".$data['to']." FROM ".$data['from']."\n";
    send_mail($data['from'], $data['to'], $data['body'], $data['subject']);

    // DELETE EMAILS
    $delete = $dbc->prepare("DELETE FROM send WHERE id = ?");
    $delete->bindParam(1, $data['id']);
    $delete->execute();
  }
} else {
  die("NO MAIL TO SEND\n");
}

?>
