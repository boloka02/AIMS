const express = require('express');
const mysql = require('mysql');
const cors = require('cors');

// Initialize the app
const app = express();

// Enable CORS
app.use(cors());

// Database connection
const db = mysql.createConnection({
  host: '34.101.76.100',  // The IP address of your MySQL server
  user: 'root',
  password: 'T0pSecret@2025!',  // Update this with your actual password
  database: 'db_ams'
});

// Connect to MySQL
db.connect(err => {
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
