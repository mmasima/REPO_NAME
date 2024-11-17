<?php
require_once(__DIR__ . '/../services/ImageService.php');

class ImageController {
    private $imageService;

    public function __construct() {
        $this->imageService = new ImageService();
    }

    public function addImage($houseId, $fileName, $description) {
        return $this->imageService->addImage($houseId, $fileName, $description);
    }

    public function deleteImage($imageId) {
        return $this->imageService->deleteImage($imageId);
    }
}
