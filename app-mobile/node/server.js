const express = require('express');
const nodemailer = require('nodemailer');
const bodyParser = require('body-parser');
const crypto = require('crypto');

const app = express();
const port = 3000;

app.get('/', (req, res) => {
  res.send('Server is working!');
});

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Setup Nodemailer transport
// const transporter = nodemailer.createTransport({
//   host: '10.100.100.19',
//   port: 110,
//   secure: false, // true for 465, false for other ports
//   auth: {
//       user: 'handheld@xeise.com', // your email
//       pass: 'hh.45',    // your email password
//   },
// });

// Simulating a storage for verification codes (in a real app, use a database)
let verificationCodes = {};

app.post('/send-code', (req, res) => {
    const email = req.body.email;
    if (!email) {
        return res.status(400).send('Email is required');
    }

    // Generate a 6-digit verification code
    const code = Math.floor(100000 + Math.random() * 900000).toString();

    // Store the code with the associated email (for simplicity, we're storing it in memory)
    verificationCodes[email] = code;

    // const transporter = nodemailer.createTransport({
    //   host: '10.100.100.19',
    //   port: 110,
    //   secure: false, // true for 465, false for other ports
    //   auth: {
    //       user: 'handheld@xeise.com', // your email
    //       pass: 'hh.45',    // your email password
    //   },
    // });

    const transporter = nodemailer.createTransport({
        service: 'gmail',
        auth: {
            user: 'leonferdianonlen@gmail.com',
            pass: '0353331274',
        },
    });

    const mailOptions = {
        from: 'handheld@xeise.com',
        to: email,
        subject: 'Your Verification Code',
        text: `Your verification code is: ${code}`,
    };

    transporter.sendMail(mailOptions, (error, info) => {
        if (error) {
            console.error('Error sending email:', error);
            return res.status(500).send('Error sending email');
        }
        res.send('Code sent successfully');
    });
});

// Route to verify code (example implementation)
app.post('/verify-code', (req, res) => {
  const email = req.body.email;
  const code = req.body.code;

  if (!email || !code) {
      return res.status(400).send('Email and code are required');
  }

  // Check if the code matches the one stored for the email
  if (verificationCodes[email] === code) {
      // Code is correct, proceed with the login or verification process
      // For example, you might want to mark the user as verified in the database
      res.send('Verification successful');
  } else {
      // Code is incorrect
      res.status(400).send('Invalid code');
  }
});


app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});
