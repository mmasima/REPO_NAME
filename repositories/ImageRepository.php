<?php
class ImageRepository {
    private $db;

    public function __construct() {
        $this->db = (new DatabaseConnection())->getConnection();
    }

    public function create($houseId, $fileName, $description) {
        $stmt = $this->db->prepare('INSERT INTO house_images (house_id, image_path, description) VALUES (?, ?, ?)');
        return $stmt->execute([$houseId, $fileName, $description]);
    }

    public function delete($imageId) {
        $stmt = $this->db->prepare('DELETE FROM house_images WHERE id = ?');
        return $stmt->execute([$imageId]);
    }
}
