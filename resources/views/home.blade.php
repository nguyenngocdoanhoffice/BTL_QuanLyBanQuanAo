@extends('layouts.app')

@section('title', 'Thời trang nam nữ hiện đại')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;

        $heroBackground = $heroBanner? Storage::url($heroBanner->image_path) : null;
    @endphp
<section class="relative z-0 bg-linear-to-b from-slate-900 via-slate-800 to-slate-900 text-white"
@if ($heroBackground)
style="background-image: linear-gradient(180deg, rgba(15,23,42,0.75), rgba(30,41,59,0.85)), url('{{ $heroBackground }}'); background-size: cover; background-position: center;"
@endif>        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 grid gap-10 lg:grid-cols-[1.1fr,0.9fr] items-center">
            <div class="space-y-6">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/20 px-4 py-2 text-xs uppercase tracking-[0.3em] text-white/70">Bộ sưu tập mới</p>
                <h1 class="text-4xl font-semibold leading-tight sm:text-5xl">DOCHA Fashion · Bộ sưu tập Xuân Hè 2026</h1>
                <p class="text-lg text-white/80">1000+ sản phẩm nam nữ, cập nhật xu hướng với chất liệu cao cấp, giao nhanh toàn quốc.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-3 text-base font-semibold text-slate-900">Khám phá ngay</a>
                    <a href="#new-arrivals" class="inline-flex items-center justify-center rounded-full border border-white/40 px-6 py-3 text-base font-semibold text-white">Hàng mới về</a>
                    <a href="#trending" class="inline-flex items-center justify-center rounded-full border border-white/40 px-6 py-3 text-base font-semibold text-white">Sản phẩm hot</a>
                    <a href="#sale" class="inline-flex items-center justify-center rounded-full border border-white/40 px-6 py-3 text-base font-semibold text-white">Khuyến mãi</a>
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
            <div class="relative z-0">
                @php($heroTrending = $trending->take(6))
                @if ($heroTrending->isEmpty())
                    <div class="rounded-3xl bg-white/10 p-6 text-white/80">Chưa có sản phẩm trending.</div>
                @else
                    <div class="overflow-hidden rounded-3xl" data-hero-slider>
                        <div class="flex transition-transform duration-500 ease-out" data-hero-slider-track>
                            @foreach ($heroTrending as $product)
                                @php($primaryImage = $product->cover_image ?: optional($product->images->first())->path)
                                @php($cardImage = $primaryImage ? Storage::url($primaryImage) : 'https://placehold.co/1200x700?text=DOCHA+San+pham')
                                <a href="{{ route('products.show', $product) }}" class="relative h-72 w-full shrink-0 bg-cover bg-center" style="background-image: url('{{ $cardImage }}')">
                                    <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/30 to-transparent"></div>
                                    <div class="relative flex h-full items-end p-6">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.3em] text-white/70">Trending</p>
                                            <h3 class="mt-2 text-2xl font-semibold">{{ $product->title }}</h3>
                                            <div class="mt-3 inline-flex items-center rounded-full bg-white/20 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-white">Xem ngay</div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" class="absolute left-4 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/20 p-2 text-white hover:bg-white/30" data-hero-slider-prev aria-label="Truoc">
                        <span aria-hidden="true">‹</span>
                    </button>
                    <button type="button" class="absolute right-4 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/20 p-2 text-white hover:bg-white/30" data-hero-slider-next aria-label="Tiep">
                        <span aria-hidden="true">›</span>
                    </button>

                    <div class="mt-4 flex items-center justify-center gap-2" data-hero-slider-dots>
                        @foreach ($heroTrending as $index => $product)
                            <button type="button" class="h-2.5 w-2.5 rounded-full bg-white/30 transition" data-hero-slider-dot data-index="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if ($heroTrending->isNotEmpty())
        @push('scripts')
            <script>
                (() => {
                    const slider = document.querySelector('[data-hero-slider]');
                    const track = document.querySelector('[data-hero-slider-track]');
                    const prevButton = document.querySelector('[data-hero-slider-prev]');
                    const nextButton = document.querySelector('[data-hero-slider-next]');
                    const dots = Array.from(document.querySelectorAll('[data-hero-slider-dot]'));

                    if (!slider || !track || dots.length === 0) {
                        return;
                    }

                    let index = 0;
                    let timerId = null;

                    const goTo = (nextIndex) => {
                        index = (nextIndex + dots.length) % dots.length;
                        const slideWidth = slider.clientWidth;
                        track.style.transform = `translateX(-${slideWidth * index}px)`;
                        dots.forEach((dot, i) => {
                            dot.classList.toggle('bg-white', i === index);
                            dot.classList.toggle('bg-white/30', i !== index);
                        });
                    };

                    const startAuto = () => {
                        stopAuto();
                        timerId = window.setInterval(() => goTo(index + 1), 4500);
                    };

                    const stopAuto = () => {
                        if (timerId) {
                            window.clearInterval(timerId);
                            timerId = null;
                        }
                    };

                    dots.forEach((dot) => {
                        dot.addEventListener('click', () => {
                            const target = Number(dot.getAttribute('data-index'));
                            if (!Number.isNaN(target)) {
                                goTo(target);
                                startAuto();
                            }
                        });
                    });

                    prevButton?.addEventListener('click', () => {
                        goTo(index - 1);
                        startAuto();
                    });

                    nextButton?.addEventListener('click', () => {
                        goTo(index + 1);
                        startAuto();
                    });

                    window.addEventListener('resize', () => goTo(index), { passive: true });

                    goTo(0);
                    startAuto();

                    slider.addEventListener('mouseenter', stopAuto);
                    slider.addEventListener('mouseleave', startAuto);
                })();
            </script>
        @endpush
    @endif

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
