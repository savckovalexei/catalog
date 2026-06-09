<?php

declare(strict_types=1);

namespace App\DTO;

class CategoryData extends BaseDTO
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description = null,
        public readonly bool $is_active = true
    ) {}
    
    public static function fromModel(Category $category): self
    {
        return new self(
            id: $category->id,
            name: $category->name,
            slug: $category->slug,
            description: $category->description,
            is_active: $category->is_active
        );
    }
}