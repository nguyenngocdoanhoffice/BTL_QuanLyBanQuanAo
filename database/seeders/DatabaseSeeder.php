<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Shop Admin',
            'email' => 'admin@shop.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'user@shop.com',
            'password' => 'password',
            'role' => 'user',
        ]);

        $categories = collect(['Men', 'Women', 'Unisex'])->map(function ($name) {
            return Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Latest {$name} styles",
            ]);
        });

        $brands = collect(['QAO Basics', 'Urban Flow', 'Luxury Line'])->map(function ($name) {
            return Brand::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'Premium quality fashion.',
            ]);
        });

        $sizes = ['S', 'M', 'L', 'XL'];

        $products = collect(range(1, 8))->map(function ($index) use ($categories, $brands, $sizes) {
            $category = $categories[$index % $categories->count()];
            $brand = $brands[$index % $brands->count()];

            $product = Product::create([
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'title' => "Fashion Item {$index}",
                'slug' => 'fashion-item-' . $index,
                'sku' => 'SKU-' . str_pad((string) $index, 4, '0', STR_PAD_LEFT),
                'short_description' => 'Lightweight fabric with modern cut.',
                'description' => 'Crafted for all-day comfort and confident looks.',
                'price' => 49.99 + $index,
                'sale_price' => $index % 2 === 0 ? 39.99 + $index : null,
                'is_trending' => $index <= 3,
                'is_new' => $index >= 6,
                'is_sale' => $index % 2 === 0,
                'size_options' => $sizes,
                'status' => 'published',
                'cover_image' => 'products/sample-' . $index . '.jpg',
            ]);

            foreach ($sizes as $size) {
                Inventory::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'quantity' => 15,
                ]);
            }

            ProductImage::create([
                'product_id' => $product->id,
                'path' => 'products/sample-' . $index . '.jpg',
                'is_primary' => true,
            ]);

            return $product;
        });

        $products->take(3)->each(function ($product, $index) {
            Discount::create([
                'product_id' => $product->id,
                'type' => 'percentage',
                'value' => 10 + ($index * 5),
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addWeeks(2),
                'is_active' => true,
            ]);
        });

        $products->take(3)->each(function ($product, $index) {
            Banner::create([
                'title' => 'Trending Drop ' . ($index + 1),
                'subtitle' => 'Explore the fresh looks everyone wants',
                'image_path' => 'banners/banner-' . ($index + 1) . '.jpg',
                'link_url' => '/products/' . $product->slug,
                'product_id' => $product->id,
                'sort_order' => $index,
            ]);
        });
    }
}
