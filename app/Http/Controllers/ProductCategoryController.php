<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::with('parent')
            ->paginate(15);

        return view('product-categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        $parentCategories = ProductCategory::active()
            ->where('parent_id', null)
            ->get();

        return view('product-categories.create', [
            'parentCategories' => $parentCategories,
        ]);
    }

    public function store(StoreProductCategoryRequest $request): RedirectResponse
    {
        ProductCategory::create($request->validated());

        return redirect()->route('product-categories.index')
            ->with('success', 'Product category created successfully.');
    }

    public function show(ProductCategory $productCategory): View
    {
        return view('product-categories.show', [
            'category' => $productCategory->load('parent', 'children', 'products'),
        ]);
    }

    public function edit(ProductCategory $productCategory): View
    {
        $parentCategories = ProductCategory::active()
            ->where('id', '!=', $productCategory->id)
            ->where('parent_id', null)
            ->get();

        return view('product-categories.edit', [
            'category' => $productCategory,
            'parentCategories' => $parentCategories,
        ]);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->update($request->validated());

        return redirect()->route('product-categories.index')
            ->with('success', 'Product category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        // Only allow deletion if no products reference it
        if ($productCategory->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with existing products. Mark as inactive instead.');
        }

        $productCategory->delete();

        return redirect()->route('product-categories.index')
            ->with('success', 'Product category deleted successfully.');
    }
}
