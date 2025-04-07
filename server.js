const express = require('express');
const mysql = require('mysql');
const cors = require('cors');
const fs = require('fs');  // Add this line to include fs module

// Initialize the app
const app = express();

// Enable CORS
app.use(cors());

// Database connection
const mysql = require('mysql');

const db = mysql.createConnection({
    host: '34.101.76.100',
    user: 'root',
    password: 'your_password', // Use the correct password here
    database: 'your_database', // The database you're connecting to
    port: 3306
});

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
