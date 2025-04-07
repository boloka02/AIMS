const express = require('express');
const mysql = require('mysql');
const cors = require('cors');

// Initialize the app
const app = express();

// Enable CORS
app.use(cors());

// Database connection
const db = mysql.createConnection({
  host: '10.184.0.2',
  user: 'root',
  password: 'T0pSecret@2025!',
  database: 'db_ams',
  port: 3306,
  connectTimeout: 10000
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
  console.log('Received request at /');  // Ensure this log is printed
  res.send('AIMS Application is running');
});

// Start the server on port 3000
app.listen(3000, '0.0.0.0', () => {
  console.log('Server listening on port 3000');
});

