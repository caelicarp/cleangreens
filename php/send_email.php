<?php

  require __DIR__ . '/vendor/autoload.php';

  use PHPMailer\PHPMailer\Exception;
  use PHPMailer\PHPMailer\PHPMailer;
  use Dotenv\Dotenv;

  $dotenv = Dotenv::createImmutable(__DIR__);
  $dotenv->load();

  if ($_SERVER['REQUEST_METHOD'] != "POST" || !empty($_POST['website_hp'])) {
    die(); // bye c:
  }

  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $subject = $_POST['subject'] ?? '';
  $message = $_POST['message'] ?? '';

  $name = trim($name);
  $email = trim($email);
  $subject = trim($subject);
  $message = trim($message);

  if (!empty($name)
      && filter_var($email, FILTER_VALIDATE_EMAIL)
      && !empty($subject)
      && !empty($message)) {
      
      $mail = new PHPMailer(true);

      try {

        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $_ENV['PORT'];

        $mail->setFrom($_ENV['MAIL_USER'], 'Clean Greens LLC ©');
        $mail->addAddress($_ENV['MAIL_TO']);
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "Contacto: $subject";

        $mail->Body = "
          <h3>Alguien contactó desde el formulario web:</h3>
          <p><strong>Nombre:</strong> $name</p>
          <p><strong>Correo:</strong> $email</p>
          <p><strong>Asunto:</strong> $subject</p>
          <p><strong>Mensaje:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
        ";

        $mail->send();
        
        header('Location: ../pages/contact.html?status=success');
        exit;
      } catch (Exception $e) {
        echo "Error al enviar: {$mail->ErrorInfo}";
      }

  } else {
    die("Datos inválidos");
  }

?>