// **SERVER-SIDE (Node.js with Express)**
const express = require('express');
const mysql = require('mysql');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json()); // To parse JSON request bodies

// **IMPORTANT: Replace with your actual database credentials**
const db = mysql.createConnection({
    host: '192.168.7.242',
    user: 'root',
    password: '',
    database: 'db_ams'
});

db.connect((err) => {
    if (err) {
        console.error('Database connection failed:', err);
        return;
    }
    console.log('Connected to db_ams database!');
});

// Get monitor progress
app.get('/api/monitor-progress', (req, res) => {
    const query = 'SELECT status FROM monitor';
    db.query(query, (err, results) => {
        if (err) {
            console.error('Error querying database:', err);
            res.status(500).json({ error: 'Failed to fetch monitor data' });
            return;
        }

        const totalMonitors = results.length;
        const assignedMonitors = results.filter(monitor => monitor.status === 'Assigned').length;
        const assignedPercentage = (totalMonitors === 0) ? 0 : (assignedMonitors / totalMonitors) * 100;

        res.json({ assignedPercentage: assignedPercentage });
    });
});

const port = 3000;
app.listen(port, () => {
    console.log(`Server listening on port ${port}`);
});
