<?php
require_once __DIR__ . '/../interfaces/IImageService.php';

class ImageService implements IImageService {
    private IDatabaseConnection $dbConnection;

    public function __construct(IDatabaseConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    public function addImage(int $houseId, string $url, string $description): bool {
        $conn = $this->dbConnection->connect();
        $stmt = $conn->prepare("INSERT INTO damage_images (house_id, url, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $houseId, $url, $description);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function deleteImage(int $imageId): bool {
        $conn = $this->dbConnection->connect();
        $stmt = $conn->prepare("DELETE FROM damage_images WHERE id = ?");
        $stmt->bind_param("i", $imageId);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        return $success;
    }

    public function getImagesByHouseId(int $houseId): array {
        $conn = $this->dbConnection->connect();
        $stmt = $conn->prepare("SELECT * FROM damage_images WHERE house_id = ?");
        $stmt->bind_param("i", $houseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $images = [];
        while ($image = $result->fetch_assoc()) {
            $images[] = $image;
        }
        $stmt->close();
        return $images;
    }
}
