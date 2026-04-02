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
        $products = Product::with(['category', 'brand'])
            ->withSum('inventories', 'quantity')
            ->latest()
            ->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'stockQuantity' => 0,
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

        $product = Product::create($data);
        $this->syncStockQuantity($product, (int) ($data['stock_quantity'] ?? 0), $data['size_options'] ?? null);

        return redirect()->route('admin.products.index')->with('status', 'Đã tạo sản phẩm.');
    }

    public function edit(Product $product): View
    {
        $product->loadSum('inventories', 'quantity');

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'stockQuantity' => (int) ($product->inventories_sum_quantity ?? 0),
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
        $this->syncStockQuantity($product, (int) ($data['stock_quantity'] ?? 0), $data['size_options'] ?? null);

        return redirect()->route('admin.products.index')->with('status', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Đã xóa sản phẩm.');
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
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'size_options' => ['nullable', 'string'],
            'is_trending' => ['sometimes', 'boolean'],
            'is_new' => ['sometimes', 'boolean'],
            'is_sale' => ['sometimes', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function syncStockQuantity(Product $product, int $quantity, ?array $sizes): void
    {
        $sizes = collect($sizes ?? [])->filter()->map(fn ($size) => (string) $size)->values()->all();

        if (empty($sizes)) {
            $product->inventories()->whereNotNull('size')->delete();
            $product->inventories()->updateOrCreate(
                ['size' => null],
                ['quantity' => max(0, $quantity)],
            );
            return;
        }

        $product->inventories()->whereNull('size')->delete();
        $product->inventories()->whereNotNull('size')->whereNotIn('size', $sizes)->delete();

        $sizeCount = count($sizes);
        $perSize = $sizeCount > 0 ? intdiv(max(0, $quantity), $sizeCount) : 0;
        $remainder = $sizeCount > 0 ? max(0, $quantity) % $sizeCount : 0;

        foreach ($sizes as $index => $size) {
            $sizeQuantity = $perSize + ($index < $remainder ? 1 : 0);
            $product->inventories()->updateOrCreate(
                ['size' => $size],
                ['quantity' => $sizeQuantity],
            );
        }
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
