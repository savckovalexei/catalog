<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\ProductData;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function index(): View
    {
        $products = $this->productService->getAllPaginated(10);
        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $data = $this->productService->getCreateData();
        return view('products.create', $data);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $productData = ProductData::fromRequest($request->validated());
        $this->productService->createProduct($productData);
        
        return redirect()->route('products.index')
            ->with('success', 'Товар успешно создан');
    }

    public function edit(Product $product): View
    {
        $data = $this->productService->getEditData($product->id);
        return view('products.edit', $data);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $productData = ProductData::fromRequest($request->validated());
        $result = $this->productService->updateProduct($product->id, $productData);
        
        if (!$result) {
            return redirect()->back()
                ->with('error', 'Товар не найден')
                ->withInput();
        }
        
        return redirect()->route('products.index')
            ->with('success', 'Товар успешно обновлен');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->deleteProduct($product->id);
        return redirect()->route('products.index')
            ->with('success', 'Товар удален');
    }
}