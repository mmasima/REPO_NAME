<?php
require_once(__DIR__ . '/../repositories/ImageRepository.php');

class ImageService {
    private $imageRepo;

    public function __construct() {
        $this->imageRepo = new ImageRepository();
    }

    public function addImage($houseId, $fileName, $description) {
        return $this->imageRepo->create($houseId, $fileName, $description);
    }

    public function deleteImage($imageId) {
        return $this->imageRepo->delete($imageId);
    }
}
