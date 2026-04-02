<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trim(
        collect([
            trim($__env->yieldContent('title')),
            config('app.name', 'QAO Shop'),
        ])->filter()->join(' · ')
    ) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900">
    @php($cartItems = auth()->check() ? collect(auth()->user()?->cart_items ?? []) : collect())
    @php($cartCount = $cartItems->count())
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-4 gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-semibold tracking-tight">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white font-bold">Q</span>
                        <span>QAO Fashion</span>
                    </a>
                    <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                        <a href="{{ route('home') }}" class="hover:text-slate-900">Trang chủ</a>
                        <a href="{{ route('products.index') }}" class="hover:text-slate-900">Sản phẩm</a>
                        <a href="#trending" class="hover:text-slate-900">Xu hướng</a>
                        <a href="#sale" class="hover:text-slate-900">Khuyến mãi</a>
                    </nav>
                    <div class="flex items-center gap-4 text-sm font-medium">
                        <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-2">
                            <span aria-hidden="true" class="text-lg">🛍️</span>
                            <span>Giỏ hàng</span>
                            <span id="cart-count" class="absolute -top-3 -right-3 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-xs font-semibold text-white">
                                {{ $cartCount }}
                            </span>
                        </a>
                        @auth
                            <div class="hidden sm:flex items-center gap-3">
                                <a href="{{ route('profile.orders') }}" class="hover:text-slate-900">Đơn hàng</a>
                                <div class="relative" data-account-menu-root>
                                    <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white uppercase" data-account-menu-toggle aria-expanded="false" aria-controls="account-menu">
                                        {{ mb_substr(auth()->user()->name ?? 'A', 0, 1) }}
                                    </button>
                                    <div id="account-menu" data-account-menu class="invisible absolute right-0 top-12 z-20 w-56 rounded-3xl border border-slate-200 bg-white p-2 opacity-0 shadow-2xl transition duration-150">
                                        <a href="{{ route('account.index') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Tài khoản</a>
                                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-rose-600 hover:bg-rose-50">
                                                Đăng xuất
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if (Route::has('login'))
                                <div class="hidden sm:flex items-center gap-3">
                                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-900">Đăng nhập</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1 rounded-full bg-slate-900 px-4 py-2 text-white">
                                            Đăng ký
                                        </a>
                                    @endif
                                </div>
                            @endif
                        @endauth
                        <button type="button" class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-600" data-drawer-toggle>
                            <span aria-hidden="true">☰</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1">
            @if (session('status'))
                <div class="bg-emerald-500 text-white">
                    <div class="max-w-4xl mx-auto px-4 py-3 text-sm font-medium">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-slate-900 text-slate-100 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 grid gap-8 md:grid-cols-3 text-sm">
                <div>
                    <p class="text-lg font-semibold">QAO Fashion</p>
                    <p class="mt-2 text-slate-400">Thời trang nam nữ hiện đại, cập nhật xu hướng mỗi tuần.</p>
                </div>
                <div>
                    <p class="font-semibold">Liên hệ</p>
                    <p class="text-slate-400">Hotline: 1900 1234<br>Email: support@qao-fashion.com</p>
                </div>
                <div>
                    <p class="font-semibold">Theo dõi chúng tôi</p>
                    <p class="text-slate-400">Facebook · Instagram · TikTok</p>
                </div>
            </div>
        </footer>
    </div>

    <div id="product-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 px-4 py-4 backdrop-blur-sm">
        <div class="absolute inset-0" data-product-preview-backdrop></div>
        <div class="relative w-full max-w-xl overflow-hidden rounded-3xl bg-white shadow-2xl">
            <button type="button" class="absolute right-3 top-3 z-10 inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-lg" data-product-preview-close aria-label="Đóng popup">×</button>

            <div class="grid gap-0 md:grid-cols-[0.72fr,1fr]">
                <div class="bg-slate-100">
                    <img src="https://placehold.co/900x1100?text=QAO+Fashion" alt="" class="h-full w-full max-h-80 object-cover" data-preview-image>
                </div>
                <div class="space-y-3 p-4 sm:p-5">
                    <div class="space-y-1.5">
                        <p class="text-xs uppercase tracking-[0.4em] text-slate-400" data-preview-category>Fashion</p>
                        <h2 class="text-xl font-semibold leading-tight" data-preview-title>Product title</h2>
                        <p class="text-xs text-slate-500" data-preview-brand>Brand</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <p class="text-lg font-bold text-slate-900" data-preview-price>0 đ</p>
                        <p class="text-xs text-slate-400 line-through hidden" data-preview-sale-price>0 đ</p>
                    </div>

                    <p class="text-xs leading-5 text-slate-600" data-preview-description></p>

                    <form method="POST" action="{{ route('cart.store') }}" class="space-y-3" data-product-preview-form>
                        @csrf
                        <input type="hidden" name="product_id" value="" data-preview-product-id>
                        <div>
                            <label class="text-xs font-medium text-slate-600">Chọn size</label>
                            <div class="mt-2 flex flex-wrap gap-2" data-preview-sizes></div>
                            <p class="mt-1.5 hidden text-xs text-slate-500" data-preview-freesize>Free-size</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-slate-600">Số lượng</label>
                            <div class="mt-1.5 flex items-center gap-2">
                                <button type="button" class="h-8 w-8 rounded-full border border-slate-200 text-sm" data-preview-qty-minus>-</button>
                                <input type="number" name="quantity" value="1" min="1" class="w-14 rounded-xl border border-slate-200 text-center text-sm" data-preview-qty>
                                <button type="button" class="h-8 w-8 rounded-full border border-slate-200 text-sm" data-preview-qty-plus>+</button>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white">Thêm vào giỏ</button>
                            <a href="#" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-700" data-preview-link>Chi tiết sản phẩm</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
