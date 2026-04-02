@extends('layouts.app')

@section('title', $product->title)

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
        $sizes = $product->inventories->pluck('size')->filter()->unique();
        if ($sizes->isEmpty()) {
            $sizes = collect($product->size_options ?? [])->filter();
        }

        $sizeInventories = $product->inventories
            ->whereNotNull('size')
            ->keyBy('size');

        $freeSizeQuantity = (int) optional($product->inventories->firstWhere('size', null))->quantity;

        $distributedQuantities = [];
        if ($sizes->isNotEmpty() && $sizeInventories->isEmpty() && $freeSizeQuantity > 0) {
            $sizeCount = $sizes->count();
            $perSize = $sizeCount > 0 ? intdiv($freeSizeQuantity, $sizeCount) : 0;
            $remainder = $sizeCount > 0 ? $freeSizeQuantity % $sizeCount : 0;

            foreach ($sizes->values() as $index => $size) {
                $distributedQuantities[(string) $size] = $perSize + ($index < $remainder ? 1 : 0);
            }
        }
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid gap-12 lg:grid-cols-2">
            <div class="space-y-4">
                @php($mainImage = $product->cover_image ? Storage::url($product->cover_image) : 'https://placehold.co/800x1000?text=QAO+Fashion')
                <img src="{{ $mainImage }}" alt="{{ $product->title }}" class="w-full rounded-[32px] border border-slate-100 object-cover shadow-xl" id="main-product-image">
                <div class="flex gap-4 overflow-x-auto">
                    @foreach ($product->images as $image)
                        <button type="button" class="h-24 w-20 shrink-0 rounded-2xl border border-slate-200" data-gallery-thumb data-image="{{ Storage::url($image->path) }}">
                            <img src="{{ Storage::url($image->path) }}" alt="{{ $product->title }}" class="h-full w-full rounded-2xl object-cover">
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="space-y-6">
                <p class="text-sm uppercase tracking-[0.3em] text-slate-400">{{ $product->category?->name }}</p>
                <h1 class="text-4xl font-semibold">{{ $product->title }}</h1>
                <p class="text-sm text-slate-500">SKU: {{ $product->sku }}</p>
                <div class="flex items-center gap-4">
                    <p class="text-3xl font-semibold text-slate-900">{{ number_format($product->final_price, 0, ',', '.') }} đ</p>
                    @if ($product->sale_price)
                        <p class="text-lg text-slate-400 line-through">{{ number_format($product->price, 0, ',', '.') }} đ</p>
                    @endif
                </div>
                <p class="text-slate-600">{{ $product->short_description }}</p>
                <form method="POST" action="{{ route('cart.store') }}" data-product-form class="space-y-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div>
                        <label class="text-sm font-medium text-slate-600">Chọn size</label>
                        <div class="mt-3 flex flex-wrap gap-3" data-size-group>
                            @forelse ($sizes as $size)
                                @php($quantityLeft = (int) (optional($sizeInventories->get($size))->quantity ?? ($distributedQuantities[(string) $size] ?? 0)))
                                <label class="inline-flex cursor-pointer">
                                    <input type="radio" name="size" value="{{ $size }}" class="peer sr-only" @checked($loop->first)>
                                    <span class="min-w-[60px] rounded-full border border-slate-200 px-4 py-2 text-center text-sm font-medium text-slate-600 transition peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white" data-size-option>
                                        {{ $size }}
                                        <span class="ml-1 text-xs font-semibold text-slate-400">({{ number_format($quantityLeft) }})</span>
                                    </span>
                                </label>
                            @empty
                                <input type="hidden" name="size" value="">
                                <span class="text-sm text-slate-500">Sản phẩm free-size (còn {{ number_format($freeSizeQuantity) }}).</span>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600">Số lượng</label>
                        <div class="mt-2 flex items-center gap-3">
                            <button type="button" class="qty-btn h-10 w-10 rounded-full border border-slate-200" data-qty-minus>-</button>
                            <input type="number" name="quantity" value="1" min="1" class="w-16 rounded-2xl border-slate-200 text-center" data-qty-input>
                            <button type="button" class="qty-btn h-10 w-10 rounded-full border border-slate-200" data-qty-plus>+</button>
                        </div>
                    </div>
                    <button type="submit" class="w-full rounded-full bg-slate-900 px-6 py-3 text-base font-semibold text-white" data-submit-add>
                        Thêm vào giỏ hàng
                    </button>
                </form>
                <article class="prose max-w-none text-slate-600">
                    {!! nl2br(e($product->description)) !!}
                </article>
            </div>
        </div>

        <section class="mt-20 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold">Sản phẩm liên quan</h2>
            </div>
            @include('products.partials.grid', ['products' => $related])
        </section>
    </div>
@endsection
