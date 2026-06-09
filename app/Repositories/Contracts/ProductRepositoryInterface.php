<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTO\ProductData;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?ProductData;
    public function create(ProductData $data): ProductData;
    public function update(int $id, ProductData $data): bool;
    public function delete(int $id): bool;
    public function syncAttributes(int $productId, array $attributes): void;
}