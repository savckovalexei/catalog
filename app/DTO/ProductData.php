<?php

declare(strict_types=1);

namespace App\DTO;

use App\Models\Product;

class ProductData extends BaseDTO
{
    public function __construct(
        public readonly int $category_id,
        public readonly string $name,
        public readonly string $sku,
        public readonly string $description,
        public readonly float $price,
        public readonly int $stock,
        public readonly bool $is_active = true,
        public readonly ?int $id = null,
        public readonly array $attributes = []
    ) {}
    
    public static function fromRequest(array $validatedData): self
    {
        return new self(
            category_id: (int) $validatedData['category_id'],
            name: trim($validatedData['name']),
            sku: trim($validatedData['sku']),
            description: trim($validatedData['description']),
            price: (float) $validatedData['price'],
            stock: (int) $validatedData['stock'],
            is_active: (bool) ($validatedData['is_active'] ?? false),
            attributes: $validatedData['attributes'] ?? [] // Просто передаем как есть
        );
    }
    
    public static function fromModel(Product $product): self
    {
        $attributes = [];
        foreach ($product->attributes as $attribute) {
            $attributes[$attribute->id] = $attribute->pivot->value;
        }
        
        return new self(
            category_id: $product->category_id,
            name: $product->name,
            sku: $product->sku,
            description: $product->description,
            price: (float) $product->price,
            stock: $product->stock,
            is_active: $product->is_active,
            id: $product->id,
            attributes: $attributes
        );
    }
    
    public function toArray(): array
    {
        return [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'attributes' => $this->attributes,
        ];
    }
}