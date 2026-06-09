<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $productId = $product ? $product->id : null;
        
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:200'],
            'sku' => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('products', 'sku')->ignore($productId) // Игнорируем текущий товар
            ],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'attributes' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Выберите категорию',
            'category_id.exists' => 'Выбранная категория не существует',
            'name.required' => 'Название обязательно',
            'sku.required' => 'Артикул обязателен',
            'sku.unique' => 'Товар с таким артикулом уже существует',
            'description.required' => 'Описание обязательно',
            'price.required' => 'Цена обязательна',
            'stock.required' => 'Количество обязательно',
        ];
    }
    
    protected function prepareForValidation(): void
    {
        // Фильтруем пустые атрибуты
        if ($this->has('attributes')) {
            $attributes = array_filter($this->input('attributes'), function ($value) {
                return !empty($value) || $value === '0';
            });
            $this->merge(['attributes' => $attributes]);
        }
        
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }
}