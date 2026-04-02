@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Đơn hàng</p>
            <h1 class="text-4xl font-semibold">Lịch sử mua hàng</h1>
        </div>
        <div class="space-y-4">
            @forelse ($orders as $order)
                <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-500">Mã đơn</p>
                            <p class="text-xl font-semibold">{{ $order->code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Trạng thái</p>
                            <p class="text-base font-semibold">{{ $order->status_label }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Thanh toán</p>
                            <p class="text-base font-semibold">{{ strtoupper($order->payment_method) }} · {{ $order->payment_status }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Tổng tiền</p>
                            <p class="text-xl font-semibold">{{ number_format($order->total, 0, ',', '.') }} đ</p>
                        </div>
                        <div>
                            <a href="{{ route('profile.orders.show', $order) }}" class="inline-flex items-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Xem chi tiết</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-10 text-center text-slate-500">
                    Bạn chưa có đơn hàng nào.
                </div>
            @endforelse
        </div>
        {{ $orders->links() }}
    </div>
@endsection
