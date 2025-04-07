const express = require('express');
const mysql = require('mysql');  // Only need this line once
const cors = require('cors');
const fs = require('fs');  // Add this line to include fs module

// Initialize the app
const app = express();

// Enable CORS
app.use(cors());

// Database connection
const db = mysql.createConnection({
  host: '34.101.76.100',
  user: 'root',
  password: 'T0pSecret@2025!',
  database: 'db_ams',
  port: 3306,
  connectTimeout: 10000  // Set to 10 seconds
});


// Connect to the database
db.connect((err) => {
    if (err) {
        console.error('Database connection failed: ' + err.stack);
        return;
    }
    console.log('Connected to database.');
});

// Set up a basic route
app.get('/', (req, res) => {
  console.log('Received request at /');
  res.send('AIMS Application is running');
});


// Start the server on port 3000
app.listen(3000, () => {
  console.log('Server listening on port 3000');
});
