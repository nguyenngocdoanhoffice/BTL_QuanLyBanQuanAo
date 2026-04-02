@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
    @php
        $shippingFee = $cart['subtotal'] >= 1000000 ? 0 : 39000;
        $orderTotal = $cart['subtotal'] + $shippingFee;
    @endphp
    <section class="relative isolate overflow-hidden bg-linear-to-b from-slate-900 via-slate-900 to-slate-800 text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.4em] text-slate-400">DOCHA Fashion</p>
                    <h1 class="mt-3 text-4xl font-semibold">Hoàn tất đơn hàng của bạn</h1>
                    <p class="mt-3 text-slate-300">Nhập thông tin giao hàng, chọn phương thức thanh toán và kiểm tra lại sản phẩm trước khi xác nhận.</p>
                </div>
                <div class="grid grid-cols-3 gap-3 text-sm">
                    <div class="rounded-2xl border border-white/10 bg-white/10 px-4 py-3">
                        <p class="text-slate-300">Bước 1</p>
                        <p class="text-lg font-semibold">Thông tin</p>
                    </div>
                    <div class="rounded-2xl border border-white/20 bg-white/5 px-4 py-3">
                        <p class="text-slate-300">Bước 2</p>
                        <p class="text-lg font-semibold">Thanh toán</p>
                    </div>
                    <div class="rounded-2xl border border-white/20 bg-white/5 px-4 py-3">
                        <p class="text-slate-300">Bước 3</p>
                        <p class="text-lg font-semibold">Hoàn tất</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="relative z-10 -mt-14 pb-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1.45fr,0.75fr]">
                <form method="POST" action="{{ route('checkout.store') }}" class="space-y-8 rounded-4xl border border-orange-100 bg-orange-50 p-8 shadow-lg">
                    @csrf

                    <div class="space-y-3">
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Thông tin liên hệ</p>
                        <h2 class="text-2xl font-semibold">Người nhận hàng</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-slate-600">Họ tên</label>
                                <input type="text" name="customer_name" value="{{ old('customer_name', $user->name) }}" class="mt-2 w-full rounded-2xl border border-black bg-orange-50 px-4 py-3 focus:border-orange-500 focus:ring-orange-500" required>
                                @error('customer_name')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-600">Số điện thoại</label>
                                <input type="text" name="customer_phone" value="{{ old('customer_phone', $user->phone) }}" class="mt-2 w-full rounded-2xl border border-black bg-orange-50 px-4 py-3 focus:border-orange-500 focus:ring-orange-500" required>
                                @error('customer_phone')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-600">Email</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email', $user->email) }}" class="mt-2 w-full rounded-2xl border border-black bg-orange-50 px-4 py-3 focus:border-orange-500 focus:ring-orange-500" required>
                                @error('customer_email')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-600">Thành phố / Tỉnh</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city', $user->city) }}" class="mt-2 w-full rounded-2xl border border-black bg-orange-50 px-4 py-3 focus:border-orange-500 focus:ring-orange-500">
                                @error('shipping_city')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Địa chỉ</p>
                        <h2 class="text-2xl font-semibold">Điểm giao hàng</h2>
                        <div>
                            <label class="text-sm font-medium text-slate-600">Địa chỉ chi tiết</label>
                            <textarea name="shipping_address" rows="3" class="mt-2 w-full rounded-2xl border border-black bg-orange-50 px-4 py-3 focus:border-orange-500 focus:ring-orange-500" required>{{ old('shipping_address', $user->address) }}</textarea>
                            @error('shipping_address')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-slate-600">Mã bưu điện</label>
                                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code', $user->postal_code) }}" class="mt-2 w-full rounded-2xl border border-black bg-orange-50 px-4 py-3 focus:border-orange-500 focus:ring-orange-500">
                                @error('shipping_postal_code')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-600">Mã giảm giá</label>
                                <div class="mt-2 flex gap-3">
                                    <input type="text" name="discount_code" value="{{ old('discount_code') }}" class="w-full rounded-2xl border border-black bg-orange-50 px-4 py-3 focus:border-orange-500 focus:ring-orange-500" placeholder="SUMMER10">
                                    <span class="inline-flex items-center rounded-2xl bg-orange-600 px-4 text-sm font-semibold text-white">Áp dụng</span>
                                </div>
                                @error('discount_code')<p class="text-sm text-rose-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-600">Ghi chú thêm</label>
                            <textarea name="notes" rows="3" class="mt-2 w-full rounded-2xl border border-black bg-orange-50 px-4 py-3 focus:border-orange-500 focus:ring-orange-500" placeholder="Ví dụ: Giao giờ hành chính, gọi bảo vệ..."></textarea>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Thanh toán</p>
                        <h2 class="text-2xl font-semibold">Phương thức mong muốn</h2>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 px-4 py-4 transition hover:border-slate-900">
                                <input type="radio" name="payment_method" value="cod" class="mt-1 text-slate-900" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                <span>
                                    <span class="font-semibold text-slate-900">Thanh toán khi nhận hàng</span>
                                    <span class="mt-1 block text-sm text-slate-500">Áp dụng toàn quốc, miễn phí thu hộ.</span>
                                </span>
                            </label>
                            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 px-4 py-4 transition hover:border-slate-900">
                                <input type="radio" name="payment_method" value="online" class="mt-1 text-slate-900" {{ old('payment_method') === 'online' ? 'checked' : '' }}>
                                <span>
                                    <span class="font-semibold text-slate-900">Thanh toán online (Mô phỏng)</span>
                                    <span class="mt-1 block text-sm text-slate-500">Visa / Mastercard / Ví điện tử được mô phỏng.</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-full bg-slate-900 px-6 py-4 text-base font-semibold text-white shadow-lg shadow-slate-900/40">Xác nhận &amp; đặt hàng</button>
                </form>

                <aside class="space-y-6 rounded-4xl border border-orange-100 bg-orange-50 p-8 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">Tóm tắt</p>
                            <h2 class="text-2xl font-semibold">Đơn hàng của bạn</h2>
                        </div>
                        <span class="rounded-full bg-slate-900 px-4 py-1 text-sm font-semibold text-white">{{ $cart['count'] }} sản phẩm</span>
                    </div>

                    <div class="space-y-4">
                        @forelse ($cart['items'] as $item)
                            <div class="flex gap-4 rounded-2xl border border-orange-100 bg-orange-50/70 p-4">
                                <div class="h-16 w-16 rounded-xl bg-white">
                                    @if (!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="h-full w-full rounded-xl object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center rounded-xl bg-slate-100 text-slate-400 text-xs">DOCHA</div>
                                    @endif
                                </div>
                                <div class="flex-1 text-sm">
                                    <p class="font-semibold text-slate-900">{{ $item['name'] }}</p>
                                    <p class="text-slate-500">x{{ $item['quantity'] }} · Size {{ $item['size'] ?? 'Một size' }}</p>
                                </div>
                                <p class="text-sm font-semibold">{{ number_format($item['subtotal'], 0, ',', '.') }} đ</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Chưa có sản phẩm nào trong giỏ hàng.</p>
                        @endforelse
                    </div>

                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-500">Tạm tính</dt>
                            <dd class="font-semibold text-slate-900">{{ number_format($cart['subtotal'], 0, ',', '.') }} đ</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-500">Giảm giá</dt>
                            <dd class="font-semibold text-emerald-600">{{ old('discount_code') ? '-Áp dụng mã' : '-0 đ' }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-slate-500">Phí vận chuyển</dt>
                            <dd class="font-semibold text-slate-900">{{ $shippingFee === 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . ' đ' }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-base">
                            <dt class="font-semibold text-slate-900">Tổng cộng</dt>
                            <dd class="text-2xl font-semibold text-slate-900">{{ number_format($orderTotal, 0, ',', '.') }} đ</dd>
                        </div>
                    </dl>

                    <div class="rounded-3xl border border-orange-100 bg-orange-50/70 p-4 text-sm text-slate-600">
                        <p class="font-semibold text-slate-900">An tâm mua sắm</p>
                        <ul class="mt-2 space-y-1">
                            <li>• Đổi trả trong 7 ngày nếu sản phẩm lỗi.</li>
                            <li>• Đóng gói thân thiện môi trường, theo dõi hành trình thời gian thực.</li>
                            <li>• Hỗ trợ 24/7 qua hotline 1900 1234.</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
