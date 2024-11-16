<?php
require_once __DIR__ . '/../interfaces/IImageService.php';

class ImageService implements IImageService {
    private IDatabaseConnection $dbConnection;

    public function __construct(IDatabaseConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function addImage(int $houseId, string $url, string $description): bool {
        $conn = $this->dbConnection->connect();
        if ($conn === null) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to connect to the database"]);
            return false;
        }
    
        $stmt = $conn->prepare("INSERT INTO damage_images (house_id, url, description) VALUES (?, ?, ?)");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to prepare the statement"]);
            return false;
        }

        $stmt->bind_param("iss", $houseId, $url, $description);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    public function deleteImage(int $imageId): bool {
        $conn = $this->dbConnection->connect();
        if ($conn === null) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to connect to the database"]);
            return false;
        }

        $stmt = $conn->prepare("DELETE FROM damage_images WHERE id = ?");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to prepare the statement"]);
            return false;
        }

        $stmt->bind_param("i", $imageId);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $success;
    }

    public function getImagesByHouseId(int $houseId): array {
        $conn = $this->dbConnection->connect();
        if ($conn === null) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to connect to the database"]);
            return [];
        }

        $stmt = $conn->prepare("SELECT * FROM damage_images WHERE house_id = ?");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to prepare the statement"]);
            return [];
        }

        $stmt->bind_param("i", $houseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $images = [];
        while ($image = $result->fetch_assoc()) {
            $images[] = $image;
        }
        $stmt->close();
        $conn->close();
        return $images;
    }
}
