<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Product::published()->with(['category', 'brand', 'images', 'inventories']);

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->integer('category')) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId = $request->integer('brand')) {
            $query->where('brand_id', $brandId);
        }

        if ($priceMin = $request->float('price_min')) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax = $request->float('price_max')) {
            $query->where('price', '<=', $priceMax);
        }

        if ($request->string('sort')->toString() === 'price_asc') {
            $query->orderBy('price');
        } elseif ($request->string('sort')->toString() === 'price_desc') {
            $query->orderByDesc('price');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('products.partials.grid', compact('products'))->render(),
            ]);
        }

        return view('products.index', [
            'products' => $products,
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
            'filters' => $request->only(['search', 'category', 'brand', 'price_min', 'price_max', 'sort']),
        ]);
    }

    public function show(Product $product): View
    {
        $product->load(['images', 'category', 'brand', 'inventories' => function ($query) {
            $query->orderBy('size');
        }]);

        $related = Product::published()
            ->with(['images', 'inventories'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }
}
