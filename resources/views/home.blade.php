@extends('layouts.app')

@section('title', 'Thời trang nam nữ hiện đại')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;

        $heroBackground = $heroBanner? Storage::url($heroBanner->image_path) : null;
    @endphp
<section class="bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white"
@if ($heroBackground)
style="background-image: linear-gradient(180deg, rgba(15,23,42,0.75), rgba(30,41,59,0.85)), url('{{ $heroBackground }}'); background-size: cover; background-position: center;"
@endif>        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 grid gap-10 lg:grid-cols-[1.1fr,0.9fr] items-center">
            <div class="space-y-6">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/20 px-4 py-2 text-xs uppercase tracking-[0.3em] text-white/70">New Drop</p>
                <h1 class="text-4xl font-semibold leading-tight sm:text-5xl">QAO Fashion · Bộ sưu tập Xuân Hè 2026</h1>
                <p class="text-lg text-white/80">1000+ sản phẩm nam nữ, cập nhật xu hướng với chất liệu cao cấp, giao nhanh toàn quốc.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-base font-semibold text-slate-900">Khám phá ngay</a>
                    <a href="#trending" class="inline-flex items-center justify-center rounded-full border border-white/40 px-6 py-3 text-base font-semibold text-white">Sản phẩm hot</a>
                </div>
                <dl class="grid grid-cols-3 gap-4 pt-6 text-white/80">
                    <div>
                        <dt class="text-3xl font-semibold">48h</dt>
                        <dd class="text-sm">Giao hàng</dd>
                    </div>
                    <div>
                        <dt class="text-3xl font-semibold">4.9/5</dt>
                        <dd class="text-sm">1.2K+ đánh giá</dd>
                    </div>
                    <div>
                        <dt class="text-3xl font-semibold">+25</dt>
                        <dd class="text-sm">Thương hiệu độc quyền</dd>
                    </div>
                </dl>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                @php($heroTrending = $trending->take(4))
                @forelse ($heroTrending as $product)
                    @php($primaryImage = $product->cover_image ?: optional($product->images->first())->path)
                    @php($cardImage = $primaryImage ? Storage::url($primaryImage) : 'https://placehold.co/600x400?text=QAO+Product')
                    <a href="{{ route('products.show', $product) }}" class="relative flex h-60 items-end rounded-3xl bg-cover bg-center shadow-2xl" style="background-image: url('{{ $cardImage }}')">
                        <div class="w-full rounded-3xl rounded-t-none bg-gradient-to-t from-black/80 via-black/20 to-transparent p-4">
                            <div class="mt-3 inline-flex items-center rounded-full bg-white/20 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white">Xem ngay</div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-3xl bg-white/10 p-6 text-white/80">Chưa có sản phẩm trending.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="trending" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-rose-500">Trending</p>
                <h2 class="text-3xl font-semibold">Sản phẩm nổi bật</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Xem tất cả →</a>
        </div>
        @include('products.partials.grid', ['products' => $trending])
    </section>

    <section class="bg-white py-16" id="new-arrivals">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">New</p>
                    <h2 class="text-3xl font-semibold">Hàng mới về</h2>
                </div>
            </div>
            @include('products.partials.grid', ['products' => $newArrivals])
        </div>
    </section>

    <section id="sale" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-500">Sale</p>
                <h2 class="text-3xl font-semibold">Giảm giá hôm nay</h2>
            </div>
        </div>
        @include('products.partials.grid', ['products' => $sales])
    </section>
@endsection
