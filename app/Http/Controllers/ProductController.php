<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category', 'unit')
            ->paginate(15);

        return view('products.index', [
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        $categories = ProductCategory::active()->get();
        $units = Unit::all();

        return view('products.create', [
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product->load('category', 'unit'),
        ]);
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::active()->get();
        $units = Unit::all();

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Only allow deletion if no documents reference it
        if ($product->documentItems()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete product with existing transactions. Mark as inactive instead.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
