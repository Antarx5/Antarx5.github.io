<?php
class Database {
    private $hostname;
    private $database;
    private $port;
    private $username;
    private $password;
    private $conn;

    /**
     * Konstruktor klasy Database.
     * 
     * @param string $hostname
     * @param string $database
     * @param string $port
     * @param string $username
     * @param string $password
     */
    public function __construct($hostname, $database, $port, $username, $password) {
        $this->hostname = $hostname;
        $this->database = $database;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->url = sprintf("mysql:host=%s;port=%s;dbname=%s", $hostname, $port, $database);
    }

    /**
     * Łączy się z bazą danych MySQL.
     * 
     * @return PDO
     * @throws Exception
     */
    public function connect() {
        try {
            $this->conn = new PDO($this->url, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            throw new Exception("MySQL connection error: " . $e->getMessage());
        }
    }

    /**
     * Pobiera wszystkie dane z tabeli _party.
     * 
     * @return array
     * @throws Exception
     */
    public function getAllPartyData() {
        try {
            $stmt = $this->conn->query("SELECT * FROM _party");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error executing query: " . $e->getMessage());
        }
    }
}

// Główna część kodu
$hostname = "fc8.h.filess.io";
$database = "Nerdshit_highestgo";
$port = "3307";
$username = "Nerdshit_highestgo";
$password = "6abe9d934c104a4a0c39e63a6b2d056dfdcb18a5";

$db = new Database($hostname, $database, $port, $username, $password);

try {
    $conn = $db->connect();
    echo "Connected successfully\n";

    $partyData = $db->getAllPartyData();
    
    if (!empty($partyData)) {
        echo "Data from _party table:\n";
        foreach ($partyData as $row) {
            echo implode(", ", $row) . "\n";
        }
    } else {
        echo "No data found in _party table.\n";
    }
} catch (Exception $e) {
    echo "An error occurred:\n";
    echo $e->getMessage() . "\n";
}
?>
