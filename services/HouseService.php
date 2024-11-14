<?php
require_once __DIR__ . '/../interfaces/IHouseService.php';
require_once __DIR__ . '/../config/DatabaseConnection.php';

class HouseService implements IHouseService {
    private IDatabaseConnection $dbConnection;

    public function __construct(IDatabaseConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function addHouse(string $address, string $description, string $geolocation): int {
        $conn = $this->dbConnection->connect();
        $stmt = $conn->prepare("INSERT INTO houses (address, description, geolocation) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $address, $description, $geolocation);
        $stmt->execute();
        $houseId = $stmt->insert_id;
        $stmt->close();
        return $houseId;
    }

    public function deleteHouse(int $houseId): bool {
        $conn = $this->dbConnection->connect();
        $stmt = $conn->prepare("DELETE FROM houses WHERE id = ?");
        $stmt->bind_param("i", $houseId);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        return $success;
    }

    public function getHouse(int $houseId): ?array {
        $conn = $this->dbConnection->connect();
        $stmt = $conn->prepare("SELECT * FROM houses WHERE id = ?");
        $stmt->bind_param("i", $houseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $house = $result->fetch_assoc();
        $stmt->close();
        return $house ?: null;
    }
}
