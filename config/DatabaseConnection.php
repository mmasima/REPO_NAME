<?php
require_once(__DIR__ . '/../interfaces/IDatabaseConnection.php');

class DatabaseConnection implements IDatabaseConnection {
    private $host = 'mysql';
    private $user = 'root';
    private $password = '123qwe';
    private $dbname = 'insurance_claim';
    private $conn;

    public function __construct() {
        $this->connect();
        $this->initializeDatabase();
    }

    public function setup_db_query($query){   
        if (isset($query) == FALSE)
            return NULL;
        
        $connection = $this->getConnection();
        $connection->exec($query);
    }
    
    public function getConnection() {
        return $this->conn;
    }

    public function connect() {
        try{
            $host = $this->getHost();
            $username = $this->getUserName();
            $password = $this->getPassword();
            $dbName = $this->getDbName();

            $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->setConn($conn);
        } catch (PDOException $e){
            echo " Connection failed: ".$e->getMessage();
        }
    }


    public function setHost($host):void {
        $this->host = $host;
    }

    public function getHost(): string {
        return $this->host;
    }
    
    
    public function getUserName(): string {
        return $this->user;
    }

    public function getPassword(): string {
        return $this->password;
    }
    
    public function setConn($connection):void {
        $this->conn = $connection;
    }
    
    
    public function getDbName(): string {
        return $this->dbname;
    }

    private function initializeDatabase() {
        $this->setup_db_query('
        CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL
            )');


        $this->setup_db_query("
            CREATE TABLE IF NOT EXISTS houses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                address VARCHAR(255) NOT NULL,
                description TEXT,
                geolocation VARCHAR(255) NOT NULL
            )
        ");

        $this->setup_db_query("
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