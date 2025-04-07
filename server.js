const express = require('express');
const mysql = require('mysql');
const cors = require('cors');
const fs = require('fs');  // Add this line to include fs module

// Initialize the app
const app = express();

// Enable CORS
app.use(cors());

// Database connection
const connection = mysql.createConnection({
  host: '34.101.76.100',
  user: 'root',
  password: 'T0pSecret@2025!',
  database: 'db_ams',
  ssl: {
    ca: fs.readFileSync('/path/to/ca-cert.pem'),        // Replace with your actual file path
    key: fs.readFileSync('/path/to/client-key.pem'),   // Replace with your actual file path
    cert: fs.readFileSync('/path/to/client-cert.pem')  // Replace with your actual file path
  }
});

// Connect to MySQL
connection.connect(err => {
  if (err) {
    console.error('Database connection failed:', err.stack);
    return;
  }
  console.log('Connected to MySQL');
});

// Set up a basic route
app.get('/', (req, res) => {
  res.send('AIMS Application is running');
});

// Start the server on port 3000
app.listen(3000, () => {
  console.log('Server listening on port 3000');
});
