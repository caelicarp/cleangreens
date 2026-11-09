<?php

  require __DIR__ . '/../vendor/autoload.php';

  use PHPMailer\PHPMailer\Exception;
  use PHPMailer\PHPMailer\PHPMailer;
  use Dotenv\Dotenv;

  $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
  $dotenv->load();

  if ($_SERVER['REQUEST_METHOD'] !== "POST") {
      die("Método no permitido.");
  }

  if (!empty($_POST['website_hp'])) {
      die("Acceso denegado.");
  }

  $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
  $secretKey = '6Let_hcuAAAAAM9FtKUpMehGYn4OAYeh70jqn-xx';
  
  $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
  $options = [
      'http' => [
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query([
              'secret'   => $secretKey,
              'response' => $recaptchaResponse
          ])
      ]
  ];
  $context  = stream_context_create($options);
  $verifyResponse = @file_get_contents($verifyUrl, false, $context);
  $responseKeys = $verifyResponse ? json_decode($verifyResponse, true) : null;

  $success = $responseKeys["success"] ?? false;
  $score   = $responseKeys["score"] ?? 0;

  if (!$success || $score < 0.5) {
      die("Security check failed (reCAPTCHA). Please try again later.");
  }

  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $subject = trim($_POST['subject'] ?? '');
  $message = trim($_POST['message'] ?? '');

  if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($subject) && !empty($message)) {
      
      $mail = new PHPMailer(true);

      try {
          $mail->isSMTP();
          $mail->Host = $_ENV['MAIL_HOST'];
          $mail->SMTPAuth = true;
          $mail->Username = $_ENV['MAIL_USER'];
          $mail->Password = $_ENV['MAIL_PASSWORD'];
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
          $mail->Port = intval($_ENV['PORT']);

          $mail->setFrom($_ENV['MAIL_USER'], 'Clean Greens LLC ©');
          $mail->addAddress($_ENV['MAIL_TO']);
          $mail->addReplyTo($email, $name);

          $mail->isHTML(true);
          $mail->CharSet = 'UTF-8';
          $mail->Subject = "Contacto Web: $subject";

          $mail->Body = "
            <h3>Nuevo mensaje desde el formulario de contacto:</h3>
            <p><strong>Nombre:</strong> " . htmlspecialchars($name) . "</p>
            <p><strong>Correo:</strong> " . htmlspecialchars($email) . "</p>
            <p><strong>Asunto:</strong> " . htmlspecialchars($subject) . "</p>
            <p><strong>Mensaje:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
          ";

          $mail->send();
          
          header('Location: ../pages/contact.html?status=success');
          exit;

      } catch (Exception $e) {
          echo "Error al enviar el correo: " . htmlspecialchars($mail->ErrorInfo);
      }

  } else {
      echo "Por favor, completa todos los campos correctamente y asegúrate de que el correo sea válido.";
  }

?>