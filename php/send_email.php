<?php

  require __DIR__ . '/vendor/phpmailer/src/Exception.php';
  require __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
  require __DIR__ . '/vendor/phpmailer/src/SMTP.php';

  use PHPMailer\PHPMailer\Exception as Exception;
  use PHPMailer\PHPMailer\PHPMailer as PHPMailer;
  use PHPMailer\PHPMailer\SMTP as SMTP;

  try {
    
  } catch (Exception $e) {
    //throw
  }

  if ($_SERVER['REQUEST_METHOD'] != "POST") {
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

  if (empty($name) == false
      and empty(filter_var($email, FILTER_VALIDATE_EMAIL)) == false
      and empty($subject) == false
      and empty($message) == false ) {
      echo "exito c:";
  } else {
    die();
  }

?>