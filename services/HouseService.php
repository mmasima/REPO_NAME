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
        if ($conn === null) {
            http_response_code(500);  // Server error
            echo json_encode(["error" => "Failed to connect to the database"]);
            return -1;  // Indicating failure
        }
    
        $stmt = $conn->prepare("INSERT INTO houses (address, description, geolocation) VALUES (?, ?, ?)");
        if (!$stmt) {
            http_response_code(500);  // Server error
            echo json_encode(["error" => "Failed to prepare the statement"]);
            return -1;
        }

        $stmt->bind_param("sss", $address, $description, $geolocation);
        $stmt->execute();
        $houseId = $stmt->insert_id;
        $stmt->close();
        $conn->close();
    
        // Return the house ID
        return $houseId;
    }

    public function deleteHouse(int $houseId): bool {
        $conn = $this->dbConnection->connect();
        if ($conn === null) {
            http_response_code(500);  // Server error
            echo json_encode(["error" => "Failed to connect to the database"]);
            return false;
        }

        $stmt = $conn->prepare("DELETE FROM houses WHERE id = ?");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to prepare the statement"]);
            return false;
        }

        $stmt->bind_param("i", $houseId);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $success;
    }

    public function getHouse(int $houseId): ?array {
        $conn = $this->dbConnection->connect();
        if ($conn === null) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to connect to the database"]);
            return null;
        }

        $stmt = $conn->prepare("SELECT * FROM houses WHERE id = ?");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to prepare the statement"]);
            return null;
        }

        $stmt->bind_param("i", $houseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $house = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $house ?: null;
    }
}
