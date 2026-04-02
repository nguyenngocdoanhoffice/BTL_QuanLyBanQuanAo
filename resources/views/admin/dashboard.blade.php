@extends('layouts.admin')

@section('title', 'Bảng điều khiển')
@section('header', 'Tổng quan quản trị')

@section('content')
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Sản phẩm</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['products']) }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Danh mục</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['categories']) }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Đơn hàng</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['orders']) }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Khách hàng</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['customers']) }}</p>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Doanh thu</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($stats['revenue'], 0, '.', ',') }} VND</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Đơn hàng gần đây</h2>
                <span class="text-sm text-slate-500">{{ $recentOrders->count() }} bản ghi gần nhất</span>
            </div>
            <div class="mt-5 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-500">
                            <th class="py-3 font-medium">Mã đơn</th>
                            <th class="py-3 font-medium">Khách hàng</th>
                            <th class="py-3 font-medium">Trạng thái</th>
                            <th class="py-3 font-medium">Tổng tiền</th>
                            <th class="py-3 font-medium">Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="py-3 font-semibold">{{ $order->code }}</td>
                                <td class="py-3">{{ $order->customer_name ?? $order->user?->name ?? 'Khách vãng lai' }}</td>
                                <td class="py-3">
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold tracking-wide text-slate-600">{{ $order->status_label }}</span>
                                </td>
                                <td class="py-3 font-semibold">{{ number_format($order->total, 0, '.', ',') }} VND</td>
                                <td class="py-3">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500">Chưa có đơn hàng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Sản phẩm bán chạy</h2>
            <ul class="mt-4 space-y-4">
                @forelse ($bestSellers as $product)
                    <li class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-slate-100">
                            @if ($product->cover_image)
                                <img src="{{ asset('storage/' . $product->cover_image) }}" alt="{{ $product->title }}" class="h-full w-full rounded-2xl object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold">{{ $product->title }}</p>
                            <p class="text-sm text-slate-500">Đã bán {{ (int) ($product->sold_quantity ?? 0) }} sản phẩm</p>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">Chưa có dữ liệu bán hàng.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
