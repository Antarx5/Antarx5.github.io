<?php
// Plik table_data.php

// Wczytaj dane logowania z pliku konfiguracyjnego
$config = require 'config.php';

$hostname = $config['hostname'];
$database = $config['database'];
$port = $config['port'];
$username = $config['username'];
$password = $config['password'];

// Odczytaj zapytanie z żądania POST
$data = json_decode(file_get_contents('php://input'), true);
$query = $data['query'];

try {
    // Połączenie z bazą danych
    $dsn = "mysql:host=$hostname;port=$port;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Wykonanie zapytania SQL
    $stmt = $pdo->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Tworzenie tabeli HTML z wynikami
    if ($results) {
        echo "<table border='1'>";
        echo "<tr>";
        foreach (array_keys($results[0]) as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr>";
        foreach ($results as $row) {
            echo "<tr>";
            foreach ($row as $column) {
                // Użycie nl2br() do interpretacji <br> w tekście
                $formatted_text = str_replace("\n", "<br>", htmlspecialchars($column));
                echo "<td>" . $formatted_text . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No results found.</p>";
    }





    // Zamknięcie połączenia
    $pdo = null;
} catch (PDOException $e) {
    http_response_code(500); // Ustawienie kodu błędu HTTP
    echo "Database Error: " . htmlspecialchars($e->getMessage()); // Wyświetlenie komunikatu błędu
    exit; // Zakończenie działania skryptu po wystąpieniu błędu
}
?>
