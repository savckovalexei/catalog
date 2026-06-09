<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use App\DTO\ProductData;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Product::with(['category', 'attributes'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function findById(int $id): ?ProductData
    {
        $product = Product::with('attributes')->find($id);
        return $product ? ProductData::fromModel($product) : null;
    }

    public function create(ProductData $data): ProductData
    {
        $product = Product::create($data->toArray());
        return ProductData::fromModel($product);
    }

    public function update(int $id, ProductData $data): bool
    {
        $product = Product::find($id);
        if (!$product) {
            return false;
        }
        return $product->update($data->toArray());
    }

    public function delete(int $id): bool
    {
        $product = Product::find($id);
        if (!$product) {
            return false;
        }
        return (bool) $product->delete();
    }

    public function syncAttributes(int $productId, array $attributes): void
    {
        $product = Product::find($productId);
        if ($product) {
            $syncData = [];
            foreach ($attributes as $attributeId => $value) {
                if (!empty($value)) {
                    $syncData[$attributeId] = ['value' => $value];
                }
            }
            $product->attributes()->sync($syncData);
        }
    }
}