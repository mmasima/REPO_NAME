<?php
class HouseRepository {
    private $conn;

    public function __construct() {
        $db = new DatabaseConnection();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM houses";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function create($data) {
        $query = "INSERT INTO houses (address, description, geolocation) VALUES (:address, :description, :geolocation)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':geolocation', $data['geolocation']);
        
        return $stmt->execute();
    }

    public function delete($houseId) {
        $query = "DELETE FROM houses WHERE id = :houseId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':houseId', $houseId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
