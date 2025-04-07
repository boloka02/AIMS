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
    host: '34.101.76.100',  // Ensure this IP is correct and accessible
    user: 'root',           // Your MySQL username
    password: 'T0pSecret@2025!',  // Your MySQL password
    database: 'db_ams',  // Your database name
    port: 3306
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
  res.send('AIMS Application is running');
});

// Start the server on port 3000
app.listen(3000, () => {
  console.log('Server listening on port 3000');
});
