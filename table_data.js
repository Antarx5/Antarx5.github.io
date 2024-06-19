// server.js

const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const config = require('./config'); // Plik konfiguracyjny z danymi do logowania

const app = express();
const port = 3000;

// Parsowanie zapytań typu application/json
app.use(bodyParser.json());

// Połączenie z bazą danych MySQL
const connection = mysql.createConnection({
    host: config.hostname,
    user: config.username,
    password: config.password,
    database: config.database,
    port: config.port
});

// Obsługa żądania POST zapytania SQL
app.post('/execute-query', (req, res) => {
    const query = req.body.query;

    // Wykonanie zapytania SQL
    connection.query(query, (error, results, fields) => {
        if (error) {
            console.error('Error executing query:', error);
            res.status(500).send('An error occurred while executing query.');
            return;
        }

        // Tworzenie tabeli HTML z wynikami zapytania
        if (results.length > 0) {
            let html = '<table border="1">';
            html += '<tr>';
            for (let header of Object.keys(results[0])) {
                html += `<th>${header}</th>`;
            }
            html += '</tr>';
            for (let row of results) {
                html += '<tr>';
                for (let column of Object.values(row)) {
                    html += `<td>${column}</td>`;
                }
                html += '</tr>';
            }
            html += '</table>';
            res.send(html);
        } else {
            res.send('<p>No results found.</p>');
        }
    });
});

// Startowanie serwera
app.listen(port, () => {
    console.log(`Server listening at http://localhost:${port}`);
});
