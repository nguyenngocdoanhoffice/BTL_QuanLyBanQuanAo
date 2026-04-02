<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ trim(
            collect([trim($__env->yieldContent('title')), 'Quản trị · ' . config('app.name', 'DOCHA Fashion')])->filter()->join(' · '),
        ) }}
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
    @php($user = auth()->user())
    <div class="min-h-screen flex">
        <aside class="hidden lg:flex w-64 flex-col border-r border-slate-200 bg-white">
            <div class="px-6 py-6 border-b border-slate-100">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 text-lg font-semibold">
                    <span
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white font-bold">D</span>
                    <span>DOCHA Admin</span>
                </a>
                <p class="mt-4 text-xs uppercase tracking-wide text-slate-500">Quản lý</p>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}"
                    class="block rounded-xl px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Bảng điều khiển
                </a>
                <a href="{{ route('admin.products.index') }}"
                    class="block rounded-xl px-4 py-3 {{ request()->routeIs('admin.products.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Sản phẩm
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="block rounded-xl px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Đơn hàng
                </a>
                <a href="{{ route('admin.discounts.index') }}"
                    class="block rounded-xl px-4 py-3 {{ request()->routeIs('admin.discounts.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Mã giảm giá
                </a>

                <a href="{{ route('admin.banners.index') }}"
                    class="block rounded-xl px-4 py-3 {{ request()->routeIs('admin.banners.*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Banner trang chủ
                </a>
                <a href="{{ route('admin.reports.sales') }}"
                    class="block rounded-xl px-4 py-3 {{ request()->routeIs('admin.reports.sales*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Báo cáo bán hàng
                </a>
            </nav>
            <div class="px-6 py-6 border-t border-slate-100 text-sm text-slate-500">
                <p class="font-semibold text-slate-700">{{ $user?->name }}</p>
                <p class="text-xs">{{ $user?->email }}</p>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white border-b border-slate-200">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">{{ now()->format('d/m/Y') }}</p>
                        <h1 class="text-xl font-semibold">@yield('header', 'Trung tâm điều khiển')</h1>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-slate-600 hover:border-slate-900">
                            <span aria-hidden="true">↩</span>
                            Về cửa hàng
                        </a>
                        <div class="relative" data-account-menu-root>
                            <button type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold uppercase text-white"
                                data-account-menu-toggle aria-expanded="false" aria-controls="admin-account-menu">
                                {{ mb_substr($user?->name ?? 'A', 0, 1) }}
                            </button>
                            <div id="admin-account-menu" data-account-menu
                                class="invisible absolute right-0 top-12 z-20 w-56 rounded-3xl border border-slate-200 bg-white p-2 opacity-0 shadow-2xl transition duration-150">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Tài
                                    khoản</a>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Quản
                                    lý bán hàng</a>
                                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center rounded-2xl px-4 py-3 text-sm font-medium text-rose-600 hover:bg-rose-50">
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8 space-y-6">
                @if (session('status'))
                    <div
                        class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
