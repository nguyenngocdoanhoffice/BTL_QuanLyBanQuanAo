@extends('layouts.app')

@section('title', 'Chi tiết đơn ' . $order->code)

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm text-slate-500">Mã đơn</p>
                <h1 class="text-3xl font-semibold">{{ $order->code }}</h1>
            </div>
            <a href="{{ route('profile.orders') }}" class="inline-flex items-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">← Quay lại</a>
        </div>
        <div class="grid gap-8 lg:grid-cols-2">
            <section class="space-y-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold">Thông tin giao hàng</h2>
                <p class="text-sm text-slate-600">{{ $order->customer_name }} · {{ $order->customer_phone }}</p>
                <p class="text-sm text-slate-500">{{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
                <p class="text-sm text-slate-500">Ghi chú: {{ $order->notes ?: '—' }}</p>
                <p class="text-sm text-slate-500">Thanh toán: {{ strtoupper($order->payment_method) }} · {{ $order->payment_status }}</p>
            </section>
            <section class="space-y-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold">Trạng thái</h2>
                <p class="text-sm font-semibold">{{ $order->status_label }}</p>
                <dl class="text-sm text-slate-500">
                    <div class="flex justify-between">
                        <dt>Tạm tính</dt>
                        <dd>{{ number_format($order->subtotal, 0, ',', '.') }} đ</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Giảm giá</dt>
                        <dd>-{{ number_format($order->discount_total, 0, ',', '.') }} đ</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Phí vận chuyển</dt>
                        <dd>{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</dd>
                    </div>
                    <div class="flex justify-between text-base font-semibold text-slate-900">
                        <dt>Tổng cộng</dt>
                        <dd>{{ number_format($order->total, 0, ',', '.') }} đ</dd>
                    </div>
                </dl>
            </section>
        </div>

        <section class="space-y-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold">Sản phẩm</h2>
            <div class="divide-y divide-slate-100">
                @foreach ($order->items as $item)
                    <div class="flex flex-wrap items-center justify-between gap-4 py-4">
                        <div>
                            <p class="font-semibold">{{ $item->product_name }}</p>
                            <p class="text-sm text-slate-500">Size: {{ $item->size ?? 'Freesize' }}</p>
                        </div>
                        <div class="text-sm text-slate-500">x{{ $item->quantity }}</div>
                        <p class="font-semibold">{{ number_format($item->subtotal, 0, ',', '.') }} đ</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
