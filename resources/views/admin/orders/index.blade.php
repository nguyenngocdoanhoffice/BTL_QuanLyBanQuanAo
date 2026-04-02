@extends('layouts.admin')

@section('title', 'Orders')
@section('header', 'Manage orders')

@section('content')
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Đơn hàng</h1>
                <p class="text-sm text-slate-500">Theo dõi và cập nhật trạng thái vận chuyển.</p>
            </div>
            <form method="GET" class="flex items-center gap-3 text-sm">
                <select name="status" class="rounded-2xl border border-slate-200 px-3 py-2">
                    <option value="">Tất cả trạng thái</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($activeStatus === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-2xl bg-slate-900 px-4 py-2 font-semibold text-white">Lọc</button>
            </form>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500">
                        <th class="py-3 font-medium">Mã đơn</th>
                        <th class="py-3 font-medium">Khách hàng</th>
                        <th class="py-3 font-medium">Trạng thái</th>
                        <th class="py-3 font-medium">Tổng tiền</th>
                        <th class="py-3 font-medium">Ngày tạo</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($orders as $order)
                        <tr>
                            <td class="py-4 font-semibold">{{ $order->code }}</td>
                            <td class="py-4">{{ $order->customer_name ?? $order->user?->name ?? 'Khách' }}</td>
                            <td class="py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold tracking-wide text-slate-600">{{ $order->status_label }}</span>
                            </td>
                            <td class="py-4 font-semibold">{{ number_format($order->total, 0, ',', '.') }} đ</td>
                            <td class="py-4">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-semibold text-slate-900">Chi tiết →</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">Chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
