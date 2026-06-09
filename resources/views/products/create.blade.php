@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Добавление товара</h2>
        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900">← Назад</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Категория *</label>
                <select name="category_id" class="w-full px-3 py-2 border rounded-lg @error('category_id') border-red-500 @enderror">
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Название *</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full px-3 py-2 border rounded-lg @error('name') border-red-500 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Артикул *</label>
                <input type="text" name="sku" value="{{ old('sku') }}" 
                       class="w-full px-3 py-2 border rounded-lg @error('sku') border-red-500 @enderror">
                @error('sku') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Описание *</label>
                <textarea name="description" rows="4" 
                          class="w-full px-3 py-2 border rounded-lg @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Цена *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" 
                           class="w-full px-3 py-2 border rounded-lg @error('price') border-red-500 @enderror">
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Количество *</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" 
                           class="w-full px-3 py-2 border rounded-lg @error('stock') border-red-500 @enderror">
                    @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Характеристики</label>
                @foreach($attributes as $attribute)
                    <div class="mb-2">
                        <label class="inline-block w-32 font-medium">{{ $attribute->name }}:</label>
                        <input type="text" name="attributes[{{ $attribute->id }}]" 
                               value="{{ old('attributes.' . $attribute->id) }}"
                               class="w-64 px-3 py-1 border rounded-lg"
                               placeholder="Значение">
                    </div>
                @endforeach
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="mr-2">
                    <span class="text-gray-700">Активен</span>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Сохранить
                </button>
            </div>
        </form>
    </div>
@endsection