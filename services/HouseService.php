<?php
require_once(__DIR__ . '/../repositories/HouseRepository.php');

class HouseService {
    private $houseRepo;

    public function __construct() {
        $this->houseRepo = new HouseRepository(); 
    }

    public function addHouse($data) {
        return $this->houseRepo->create($data);
    }

    public function deleteHouse($houseId) {
        return $this->houseRepo->delete($houseId);
    }

    public function getAllHouses() {
        return $this->houseRepo->getAll(); 
    }
}
