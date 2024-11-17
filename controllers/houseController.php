<?php
require_once(__DIR__ . '/../services/HouseService.php');

class HouseController {
    private $houseService;

    public function __construct() {
        $this->houseService = new HouseService();
    }

    public function addHouse($data) {
        if (!isset($data['address']) || !isset($data['geolocation'])) {
            Response::json(['error' => 'Missing required fields'], 400);
        }

        $result = $this->houseService->addHouse($data);
        if ($result) {
            Response::json(['message' => 'House added successfully']);
        } else {
            Response::json(['error' => 'Failed to add house'], 500);
        }
    }

    public function deleteHouse($houseId) {
        $result = $this->houseService->deleteHouse($houseId);
        if ($result) {
            Response::json(['message' => 'House deleted successfully']);
        } else {
            Response::json(['error' => 'Failed to delete house'], 500);
        }
    }

    public function getAllHouses() {
        $houses = $this->houseService->getAllHouses();
        if ($houses) {
            Response::json(['houses' => $houses]);
        } else {
            Response::json(['error' => 'No houses found'], 404);
        }
    }
}
