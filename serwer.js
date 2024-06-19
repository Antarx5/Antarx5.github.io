const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const config = require('./config');

const app = express();
//const port = 3000;

app.use(bodyParser.json());

const pool = mysql.createPool({
    connectionLimit: 10,
    host: config.hostname,
    user: config.username,
    password: config.password,
    database: config.database,
    port: config.port
});

app.post('/execute-query', (req, res) => {
    const query = req.body.query;

    pool.query(query, (error, results) => {
        if (error) {
            return res.status(500).send('Error executing query: ' + error);
        }

        if (results.length > 0) {
            let html = '<table border="1"><tr>';
            for (const header in results[0]) {
                html += `<th>${header}</th>`;
            }
            html += '</tr>';

            results.forEach(row => {
                html += '<tr>';
                for (const column in row) {
                    let cellData = row[column];
                    if (typeof cellData === 'string') {
                        cellData = cellData.replace(/<br>/g, '\n');
                    }
                    html += `<td>${cellData}</td>`;
                }
                html += '</tr>';
            });

            html += '</table>';
            res.send(html);
        } else {
            res.send('<p>No results found.</p>');
        }
    });
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}/`);
});
