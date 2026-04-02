@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    $image = $product->cover_image ? Storage::url($product->cover_image) : 'https://placehold.co/600x800?text=QAO+Fashion';
    $sizes = $product->inventories?->pluck('size')->filter()->unique()->values();

    if (($sizes?->isEmpty() ?? true) && ! empty($product->size_options)) {
        $sizes = collect($product->size_options)->filter()->values();
    }

    $previewPayload = [
        'id' => $product->id,
        'title' => $product->title,
        'brand' => $product->brand?->name ?? 'QAO',
        'category' => $product->category?->name ?? 'Fashion',
        'description' => Str::limit($product->short_description ?: $product->description, 140),
        'price' => number_format($product->final_price, 0, ',', '.') . ' đ',
        'sale_price' => $product->sale_price ? number_format($product->sale_price, 0, ',', '.') . ' đ' : null,
        'image' => $image,
        'url' => route('products.show', $product),
        'sizes' => $sizes?->values()->all() ?? [],
    ];
@endphp

<div class="group relative flex flex-col rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-1">
    <a href="{{ route('products.show', $product) }}" class="relative block overflow-hidden rounded-t-2xl">
        <span class="absolute left-3 top-3 rounded-full bg-slate-900/80 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
            {{ $product->category?->name ?? 'Fashion' }}
        </span>
        @if ($product->sale_price || $product->is_sale)
            <span class="absolute right-3 top-3 rounded-full bg-rose-500 px-3 py-1 text-xs font-semibold text-white">
                -{{ $product->sale_price ? number_format((($product->price - $product->sale_price) / $product->price) * 100, 0) : 15 }}%
            </span>
        @endif
        <img src="{{ $image }}" alt="{{ $product->title }}" class="h-72 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
    </a>
    <div class="flex flex-1 flex-col gap-2 p-4">
        <a href="{{ route('products.show', $product) }}" class="text-base font-semibold text-slate-900 line-clamp-2">{{ $product->title }}</a>
        <p class="text-sm text-slate-500">{{ $product->brand?->name ?? 'QAO' }}</p>
        <div class="flex items-center gap-3">
            <p class="text-lg font-bold text-slate-900">{{ number_format($product->final_price, 0, ',', '.') }} đ</p>
            @if ($product->sale_price)
                <p class="text-sm text-slate-400 line-through">{{ number_format($product->price, 0, ',', '.') }} đ</p>
            @endif
        </div>
        <div class="mt-auto flex items-center gap-3">
            <a href="{{ route('products.show', $product) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Chi tiết</a>
            <button type="button" class="ml-auto inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-lg transition hover:bg-slate-800" data-product-preview-open data-product-preview='@json($previewPayload)'>
                Thêm vào giỏ
            </button>
        </div>
    </div>
</div>
