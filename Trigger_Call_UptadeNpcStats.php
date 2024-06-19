<?php
        // Wczytaj dane logowania z pliku konfiguracyjnego
        $config = require 'config.php';

        $hostname = $config['hostname'];
        $database = $config['database'];
        $port = $config['port'];
        $username = $config['username'];
        $password = $config['password'];

        try {
            // Połączenie z bazą danych
            $dsn = "mysql:host=$hostname;port=$port;dbname=$database";
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Pobranie najnowszego rekordu z tabeli npc_stats_multipliers
            $stmt = $pdo->query("SELECT ID_NPC, ATK_ADD, MAG_ADD, INC_ADD, SKL_ADD, LCK_ADD, DEF_ADD, RES_ADD FROM npc_stats_multipliers ORDER BY ID_NPC DESC LIMIT 1");
            $latestRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($latestRecord) {
                $id = $latestRecord['ID_NPC'];
                $atk_add = $latestRecord['ATK_ADD'];
                $mag_add = $latestRecord['MAG_ADD'];
                $inc_add = $latestRecord['INC_ADD'];
                $skl_add = $latestRecord['SKL_ADD'];
                $lck_add = $latestRecord['LCK_ADD'];
                $def_add = $latestRecord['DEF_ADD'];
                $res_add = $latestRecord['RES_ADD'];

                // Przygotowanie i wywołanie procedury składowanej Callupdate_npc_stats
                $stmt = $pdo->prepare("CALL update_npc_stats(:id, :atk_add, :mag_add, :inc_add, :skl_add, :lck_add, :def_add, :res_add)");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':atk_add', $atk_add, PDO::PARAM_INT);
                $stmt->bindParam(':mag_add', $mag_add, PDO::PARAM_INT);
                $stmt->bindParam(':inc_add', $inc_add, PDO::PARAM_INT);
                $stmt->bindParam(':skl_add', $skl_add, PDO::PARAM_INT);
                $stmt->bindParam(':lck_add', $lck_add, PDO::PARAM_INT);
                $stmt->bindParam(':def_add', $def_add, PDO::PARAM_INT);
                $stmt->bindParam(':res_add', $res_add, PDO::PARAM_INT);
                $stmt->execute();

                echo "Procedura składowana została pomyślnie wywołana.\n";
            } else {
                echo "Brak rekordów w tabeli npc_stats_multipliers.\n";
            }

        } catch (PDOException $e) {
            echo "Błąd połączenia: " . $e->getMessage() . "\n";
        }
?>