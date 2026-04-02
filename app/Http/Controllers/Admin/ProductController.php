<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'brand'])->latest()->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('products', 'public');
        }

        $data['size_options'] = $this->prepareSizes($request->input('size_options'));
        $data['is_trending'] = $request->boolean('is_trending');
        $data['is_new'] = $request->boolean('is_new');
        $data['is_sale'] = $request->boolean('is_sale');

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request, $product);

        if ($request->hasFile('cover_image')) {
            if ($product->cover_image) {
                Storage::disk('public')->delete($product->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('products', 'public');
        }

        $data['size_options'] = $this->prepareSizes($request->input('size_options'));
        $data['is_trending'] = $request->boolean('is_trending');
        $data['is_new'] = $request->boolean('is_new');
        $data['is_sale'] = $request->boolean('is_sale');

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $id = $product?->id;

        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($id)],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'sku' => ['required', 'string', 'max:120', Rule::unique('products', 'sku')->ignore($id)],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'size_options' => ['nullable', 'string'],
            'is_trending' => ['sometimes', 'boolean'],
            'is_new' => ['sometimes', 'boolean'],
            'is_sale' => ['sometimes', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function prepareSizes(?string $sizes): ?array
    {
        if (! $sizes) {
            return null;
        }

        return collect(explode(',', $sizes))
            ->map(fn ($size) => trim($size))
            ->filter()
            ->values()
            ->all();
    }
}
