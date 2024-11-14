<?php
interface IHouseService {
    public function addHouse(string $address, string $description, string $geolocation): int;
    public function deleteHouse(int $houseId): bool;
    public function getHouse(int $houseId): ?array;
}
