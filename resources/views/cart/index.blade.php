@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    @php use Illuminate\Support\Str; @endphp
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col gap-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Giỏ hàng</p>
                <h1 class="text-4xl font-semibold"><span data-cart-page-count>{{ $count }}</span> sản phẩm</h1>
            </div>

            <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
                <div class="space-y-4" data-cart-items>
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-3xl border border-slate-100 bg-white p-4 shadow-sm">
                        <div class="flex-1 min-w-55">
                            <label for="cart-search" class="sr-only">Tìm sản phẩm trong giỏ</label>
                            <input id="cart-search" type="text" placeholder="Tìm sản phẩm trong giỏ..." class="w-full rounded-2xl border border-slate-200 px-4 py-2.5 text-sm focus:border-slate-900 focus:ring-slate-900" data-cart-search>
                        </div>
                        @if ($count > 0)
                            <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ sản phẩm trong giỏ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-full border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-600 hover:bg-rose-100">
                                    Xóa toàn bộ
                                </button>
                            </form>
                        @endif
                    </div>

                    @forelse ($items as $item)
                        <div class="flex flex-wrap gap-4 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm" data-cart-item data-key="{{ $item['key'] }}" data-price="{{ $item['price'] }}" data-item-name="{{ Str::lower($item['name']) }}">
                            <div class="shrink-0">
                                <img src="{{ $item['image'] ?? 'https://placehold.co/200x200?text=QAO' }}" alt="{{ $item['name'] }}" class="h-32 w-28 rounded-2xl object-cover">
                            </div>
                            <div class="flex flex-1 flex-col gap-2">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="text-lg font-semibold">{{ $item['name'] }}</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="text-sm text-slate-500">Size:</span>
                                    @if (!empty($item['available_sizes']))
                                        <select class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm" data-cart-size>
                                            @foreach ($item['available_sizes'] as $size)
                                                <option value="{{ $size }}" @selected(($item['size'] ?? '') === $size)>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <span class="text-sm text-slate-500">Freesize</span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-500">Giá: {{ number_format($item['price'], 0, ',', '.') }} đ</p>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="qty-btn h-9 w-9 rounded-full border border-slate-200" data-qty-minus>-</button>
                                    <input type="number" min="1" value="{{ $item['quantity'] }}" class="w-16 rounded-2xl border-slate-200 text-center" data-cart-qty>
                                    <button type="button" class="qty-btn h-9 w-9 rounded-full border border-slate-200" data-qty-plus>+</button>
                                </div>
                            </div>
                            <div class="flex min-w-30 flex-col items-end justify-between gap-3 text-right">
                                <p class="text-lg font-semibold" data-line-subtotal>{{ number_format($item['subtotal'], 0, ',', '.') }} đ</p>
                                <button type="button" class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3.5 py-2 text-xs font-semibold text-rose-600 transition hover:-translate-y-0.5 hover:bg-rose-100 hover:shadow-sm" data-remove-item>
                                    Xóa
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center text-slate-500">
                            Giỏ hàng của bạn đang trống.
                        </div>
                    @endforelse
                </div>

                <aside class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold">Tổng quan</h2>
                    <dl class="mt-6 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt>Tạm tính</dt>
                            <dd data-cart-subtotal>{{ number_format($subtotal, 0, ',', '.') }} đ</dd>
                        </div>
                        <div class="flex items-center justify-between text-slate-500">
                            <dt>Phí vận chuyển dự kiến</dt>
                            <dd>Miễn phí &gt;= 1000k</dd>
                        </div>
                    </dl>
                    <div class="mt-6 border-t border-slate-100 pt-4">
                        <div class="flex items-center justify-between text-lg font-semibold">
                            <span>Tổng</span>
                            <span data-cart-total>{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                        </div>
                    </div>
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('checkout.index') }}" class="block w-full rounded-full bg-slate-900 px-4 py-3 text-center text-base font-semibold text-white">Tiến hành thanh toán</a>
                        <a href="{{ route('products.index') }}" class="block w-full rounded-full border border-slate-200 px-4 py-3 text-center text-base font-semibold text-slate-600">Tiếp tục mua sắm</a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
