<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Категории
        $categories = [
            ['name' => 'Электроника', 'slug' => 'electronics', 'description' => 'Смартфоны, ноутбуки и гаджеты'],
            ['name' => 'Одежда', 'slug' => 'clothing', 'description' => 'Мужская и женская одежда'],
            ['name' => 'Дом и кухня', 'slug' => 'home-kitchen', 'description' => 'Товары для дома'],
        ];
        
        foreach ($categories as $category) {
            DB::table('categories')->insert($category + ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Характеристики
        $attributes = [
            ['name' => 'Цвет', 'code' => 'color', 'type' => 'text'],
            ['name' => 'Размер', 'code' => 'size', 'type' => 'text'],
            ['name' => 'Вес (кг)', 'code' => 'weight', 'type' => 'number'],
            ['name' => 'Бренд', 'code' => 'brand', 'type' => 'text'],
            ['name' => 'Гарантия (мес)', 'code' => 'warranty', 'type' => 'number'],
        ];
        
        foreach ($attributes as $attribute) {
            DB::table('attributes')->insert($attribute + ['created_at' => now(), 'updated_at' => now()]);
        }

        // Товары
        $products = [
            ['category_id' => 1, 'name' => 'iPhone 14 Pro', 'sku' => 'IP14PRO-001', 'description' => 'Флагманский смартфон Apple', 'price' => 89999.00, 'stock' => 15],
            ['category_id' => 1, 'name' => 'Samsung Galaxy S23', 'sku' => 'SGS23-002', 'description' => 'Мощный Android смартфон', 'price' => 74999.00, 'stock' => 23],
            ['category_id' => 1, 'name' => 'MacBook Air M2', 'sku' => 'MBA-M2-003', 'description' => 'Легкий и мощный ноутбук', 'price' => 119999.00, 'stock' => 8],
            ['category_id' => 2, 'name' => 'Джинсы мужские', 'sku' => 'JEANS-M-004', 'description' => 'Классические синие джинсы', 'price' => 3499.00, 'stock' => 45],
            ['category_id' => 2, 'name' => 'Футболка хлопок', 'sku' => 'TSHIRT-005', 'description' => 'Белая футболка из 100% хлопка', 'price' => 999.00, 'stock' => 120],
            ['category_id' => 2, 'name' => 'Куртка зимняя', 'sku' => 'JACKET-006', 'description' => 'Теплая пуховая куртка', 'price' => 12999.00, 'stock' => 12],
            ['category_id' => 3, 'name' => 'Сковорода', 'sku' => 'PAN-007', 'description' => 'Антипригарное покрытие', 'price' => 2499.00, 'stock' => 34],
            ['category_id' => 3, 'name' => 'Набор кастрюль', 'sku' => 'POTSET-008', 'description' => '3 кастрюли с крышками', 'price' => 5999.00, 'stock' => 7],
            ['category_id' => 3, 'name' => 'Миксер', 'sku' => 'MIXER-009', 'description' => 'Мощность 500Вт', 'price' => 3999.00, 'stock' => 18],
            ['category_id' => 1, 'name' => 'Xiaomi Mi Band 8', 'sku' => 'MIBAND-010', 'description' => 'Фитнес-браслет', 'price' => 3999.00, 'stock' => 56],
        ];

        foreach ($products as $product) {
            $productId = DB::table('products')->insertGetId($product + ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]);
            
            // Привязываем характеристики к товарам
            $attributeValues = [
                1 => ['value' => ['Черный', 'Синий', 'Серебро'][array_rand(['Черный', 'Синий', 'Серебро'])]],
                2 => ['value' => ['S', 'M', 'L', 'XL'][array_rand(['S', 'M', 'L', 'XL'])]],
                3 => ['value' => (string) rand(50, 300) . ' г'],
                4 => ['value' => ['Apple', 'Samsung', 'Xiaomi', 'Sony'][array_rand(['Apple', 'Samsung', 'Xiaomi', 'Sony'])]],
                5 => ['value' => (string) rand(6, 24)],
            ];
            
            $randomAttrs = array_rand($attributeValues, rand(2, 4));
            foreach ((array)$randomAttrs as $attrId) {
                DB::table('attribute_product')->insert([
                    'product_id' => $productId,
                    'attribute_id' => $attrId,
                    'value' => $attributeValues[$attrId]['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}