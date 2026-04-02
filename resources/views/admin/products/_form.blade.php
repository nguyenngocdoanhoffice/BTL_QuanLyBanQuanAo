@php
    $sizeValue = old('size_options', isset($product) && is_array($product->size_options) ? implode(', ', $product->size_options) : '');
    $statusValue = old('status', $product->status ?? 'draft');
    $statuses = [
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ];
@endphp

<div class="grid gap-10 lg:grid-cols-[1.3fr,0.7fr]">
    <div class="space-y-6">
        <div class="space-y-2">
            <label for="title" class="text-sm font-medium text-slate-600">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $product->title ?? '') }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
            @error('title')
                <p class="text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="slug" class="text-sm font-medium text-slate-600">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug ?? '') }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                @error('slug')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <label for="sku" class="text-sm font-medium text-slate-600">SKU</label>
                <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                @error('sku')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="category_id" class="text-sm font-medium text-slate-600">Category</label>
                <select id="category_id" name="category_id" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                    <option value="">Select category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <label for="brand_id" class="text-sm font-medium text-slate-600">Brand</label>
                <select id="brand_id" name="brand_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                    <option value="">No brand</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id ?? '') == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="price" class="text-sm font-medium text-slate-600">Price</label>
                <input type="number" id="price" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" step="0.01" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                @error('price')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <label for="sale_price" class="text-sm font-medium text-slate-600">Sale price</label>
                <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" min="0" step="0.01" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                @error('sale_price')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-2">
            <label for="stock_quantity" class="text-sm font-medium text-slate-600">Stock quantity</label>
            <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $stockQuantity ?? 0) }}" min="0" step="1" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
            <p class="text-xs text-slate-500">If sizes are provided, stock will be distributed evenly by size.</p>
            @error('stock_quantity')
                <p class="text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-2">
            <label for="short_description" class="text-sm font-medium text-slate-600">Short description</label>
            <textarea id="short_description" name="short_description" rows="3" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">{{ old('short_description', $product->short_description ?? '') }}</textarea>
            @error('short_description')
                <p class="text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-2">
            <label for="description" class="text-sm font-medium text-slate-600">Description</label>
            <textarea id="description" name="description" rows="6" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">{{ old('description', $product->description ?? '') }}</textarea>
            @error('description')
                <p class="text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label for="size_options" class="text-sm font-medium text-slate-600">Available sizes</label>
                <input type="text" id="size_options" name="size_options" value="{{ $sizeValue }}" placeholder="XS, S, M, L" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                <p class="text-xs text-slate-500">Separate by comma.</p>
                @error('size_options')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="space-y-2">
                <label for="status" class="text-sm font-medium text-slate-600">Status</label>
                <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($statusValue === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="space-y-2">
            <label for="cover_image" class="text-sm font-medium text-slate-600">Cover image</label>
            <input type="file" id="cover_image" name="cover_image" class="w-full rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-10 text-center text-sm text-slate-500">
            @error('cover_image')
                <p class="text-sm text-rose-600">{{ $message }}</p>
            @enderror
            @if (!empty($product?->cover_image))
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-3">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Current image</p>
                    <img src="{{ asset('storage/' . $product->cover_image) }}" alt="{{ $product->title }}" class="mt-3 rounded-xl">
                </div>
            @endif
        </div>
        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 space-y-3">
            <p class="text-sm font-semibold text-slate-700">Highlights</p>
            <label class="flex items-center justify-between text-sm text-slate-600">
                <span>Trending</span>
                <input type="checkbox" name="is_trending" value="1" @checked(old('is_trending', $product->is_trending ?? false)) class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
            </label>
            <label class="flex items-center justify-between text-sm text-slate-600">
                <span>New arrival</span>
                <input type="checkbox" name="is_new" value="1" @checked(old('is_new', $product->is_new ?? false)) class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
            </label>
            <label class="flex items-center justify-between text-sm text-slate-600">
                <span>Sale spotlight</span>
                <input type="checkbox" name="is_sale" value="1" @checked(old('is_sale', $product->is_sale ?? false)) class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
            </label>
        </div>
    </div>
</div>
*** End of File
