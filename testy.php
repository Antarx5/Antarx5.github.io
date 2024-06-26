<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Execute SQL Query</title>
    <script>
        function executeQuery(query) {
            // Wyślij zapytanie do table_data.php
            fetch('table_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ query: query })
            })
            .then(response => response.text())
            .then(data => {
                // Utwórz nowy div dla wyników zapytania
                const resultDiv = document.createElement('div');
                resultDiv.innerHTML = data; // Ustaw wyniki zapytania jako HTML nowego diva

                // Umieść wyniki w nowym miejscu na stronie
                const resultsContainer = document.getElementById('results-container');
                resultsContainer.appendChild(resultDiv);
            })
            .catch(error => {
                console.error('Error:', error);
                // Obsługa błędów: Dodaj komunikat o błędzie do wyników
                const errorDiv = document.createElement('div');
                errorDiv.textContent = 'An error occurred';
                document.getElementById('results-container').appendChild(errorDiv);
            });
        }

    </script>
</head>
<body>
    <div id="results-container"></div>
        <script>
        

        executeQuery("SELECT * FROM _party");

        
        executeQuery("SELECT `npc_ability`.`Name`, `npc_ability`.`Description` FROM `npc_ability` INNER JOIN `npc` ON `npc_ability`.`ID_NPC` = `npc`.`ID` INNER JOIN `player` ON `player`.`ID_NPC` = `npc`.`ID` WHERE (`player`.`ID` = 1)");
            
        </script>
</body>
</html>