require("dotenv").config();
const gmailUser = process.env.GMAIL_USER;
const gmailPassword = process.env.GMAIL_PASSWORD;
const express = require('express');
const nodemailer = require('nodemailer');
const app = express();

// Middleware que lee formularios básicos de HTML
app.use(express.urlencoded({ extended: true }));

// Configuración de conexión con Gmail
const transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: gmailUser,
    pass: gmailPassword // Gmail app password
  }
});

// Esta ruta recibe los datos del formulario
app.post('/contact', (req, res) => {
  const name = req.body.name;
  const email = req.body.email;
  const subject = req.body.subject;
  const message = req.body.message;

  // Estructura del mensaje
  const mailMessage = {
    from: email,
    to: 'ceocleangreens@gmail.com',
    subject: `Contacto: ${subject}`,
    text: `De ${name} (${email})\n\nMensaje:\n${message}`
  };

  // Enviamos finalmente el correo usando la estructura anterior
  transporter.sendMail(mailMessage, (error, info) => {
    if (error) {
      console.log(error);
      return res.status(500).send('valió queso el email');
    }
    res.send(`Thank you! ${name}, your message was received!`);
  });
});

// No olvidar inicializar el puerto al final
app.listen(3000, () => {
  console.log('Servidor corriendo en http://localhost:3000');
});
