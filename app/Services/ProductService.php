<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ProductData;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Category;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function getAllPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->getAllPaginated($perPage);
    }

    public function getCreateData(): array
    {
        return [
            'categories' => $this->categoryRepository->getActiveForSelect(),
            'attributes' => Attribute::all(),
        ];
    }

    public function createProduct(ProductData $data): ProductData
    {
        DB::beginTransaction();
        try {
            $attributes = $data->attributes;
            
            // Создаем товар без атрибутов
            $productData = new ProductData(
                category_id: $data->category_id,
                name: $data->name,
                sku: $data->sku,
                description: $data->description,
                price: $data->price,
                stock: $data->stock,
                is_active: $data->is_active,
                attributes: [] // Пустой массив для создания
            );
            
            $product = $this->productRepository->create($productData);
            
            if (!empty($attributes) && $product->id) {
                $this->productRepository->syncAttributes($product->id, $attributes);
            }
            
            DB::commit();
            
            // Возвращаем обновленный товар с атрибутами
            return $this->productRepository->findById($product->id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProduct(int $id, ProductData $data): bool
    {
        DB::beginTransaction();
        try {
            $attributes = $data->attributes;
            
            // Создаем DTO без атрибутов для обновления
            $updateData = new ProductData(
                category_id: $data->category_id,
                name: $data->name,
                sku: $data->sku,
                description: $data->description,
                price: $data->price,
                stock: $data->stock,
                is_active: $data->is_active,
                attributes: []
            );
            
            $result = $this->productRepository->update($id, $updateData);
            
            if (!$result) {
                DB::rollBack();
                return false;
            }
            
            if (!empty($attributes)) {
                $this->productRepository->syncAttributes($id, $attributes);
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }

    public function getProduct(int $id): ?ProductData
    {
        return $this->productRepository->findById($id);
    }
    
    public function getEditData(int $id): array
    {
        $product = $this->getProduct($id);
        if (!$product) {
            abort(404);
        }
        
        return [
            'product' => $product,
            'categories' => $this->categoryRepository->getActiveForSelect(),
            'attributes' => Attribute::all(),
        ];
    }
}