<?php
interface IImageService {
    public function addImage(int $houseId, string $url, string $description): bool;
    public function deleteImage(int $imageId): bool;
    public function getImagesByHouseId(int $houseId): array;
}
