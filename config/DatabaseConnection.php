<?php
require_once(__DIR__ . '/../interfaces/IDatabaseConnection.php');

class DatabaseConnection implements IDatabaseConnection {
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $dbname = 'login';
    private $conn;

    public function __construct() {
        $this->connect();
    }

    public function connect() {
        if ($this->conn === null) {
            $this->conn = new mysqli($this->host, $this->user, $this->password);

            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }

            $dbCheck = $this->conn->query("SHOW DATABASES LIKE '$this->dbname'");
            if ($dbCheck->num_rows === 0) {
                $this->conn->query("CREATE DATABASE $this->dbname");
            }

            $this->conn->select_db($this->dbname);
        }

        return $this->conn;
    }

    private function initializeDatabase() {
        $this->connect();
        $this->conn->query("
            CREATE TABLE IF NOT EXISTS houses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                address VARCHAR(255) NOT NULL,
                description TEXT,
                geolocation VARCHAR(255) NOT NULL
            )
        ");
    
        $this->conn->query("
            CREATE TABLE IF NOT EXISTS damage_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                house_id INT NOT NULL,
                url VARCHAR(255) NOT NULL,
                description TEXT,
                FOREIGN KEY (house_id) REFERENCES houses(id) ON DELETE CASCADE
            )
        ");
    }
    
}
