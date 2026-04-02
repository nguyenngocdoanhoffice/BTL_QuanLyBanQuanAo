@extends('layouts.admin')

@section('title', 'Order ' . $order->code)
@section('header', 'Chi tiết đơn hàng')

@section('content')
    @if (session('status'))
        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold">Vui lòng kiểm tra lại:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
        <section class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-slate-500">Mã đơn</p>
                    <h1 class="text-3xl font-semibold tracking-tight">{{ $order->code }}</h1>
                </div>
                <div class="text-right">
                    <p class="text-sm text-slate-500">Ngày tạo</p>
                    <p class="text-base font-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <h2 class="text-lg font-semibold">Người nhận</h2>
                    <p class="text-sm text-slate-600">{{ $order->customer_name }} · {{ $order->customer_phone }}</p>
                    <p class="text-sm text-slate-500">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Địa chỉ giao</h2>
                    <p class="text-sm text-slate-600">{{ $order->shipping_address }}</p>
                    <p class="text-sm text-slate-500">{{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Thanh toán</p>
                    <p class="text-base font-semibold">{{ strtoupper($order->payment_method) }} · {{ $order->payment_status }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Ghi chú</p>
                    <p class="text-base text-slate-700">{{ $order->notes ?: '—' }}</p>
                </div>
            </div>
        </section>

        <section class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <h2 class="text-lg font-semibold">Trạng thái vận đơn</h2>
                <p class="text-sm text-slate-500">Chọn một trong các bước xử lý để đồng bộ với khách hàng.</p>
            </div>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <select name="status" class="w-full rounded-2xl border border-slate-200 px-4 py-3">
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Cập nhật trạng thái</button>
            </form>
            <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
                <p>Các trạng thái khả dụng:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($statuses as $label)
                        <li>{{ $label }}</li>
                    @endforeach
                </ul>
            </div>
        </section>
    </div>

    <section class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Sản phẩm trong đơn</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-slate-500">
                    <tr>
                        <th class="py-3 font-medium">Sản phẩm</th>
                        <th class="py-3 font-medium">SKU</th>
                        <th class="py-3 font-medium">Size</th>
                        <th class="py-3 font-medium">Số lượng</th>
                        <th class="py-3 font-medium">Đơn giá</th>
                        <th class="py-3 font-medium">Tạm tính</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach ($order->items as $item)
                        <tr>
                            <td class="py-4 font-semibold">{{ $item->product_name }}</td>
                            <td class="py-4">{{ $item->sku }}</td>
                            <td class="py-4">{{ $item->size ?? 'Freesize' }}</td>
                            <td class="py-4">{{ $item->quantity }}</td>
                            <td class="py-4">{{ number_format($item->unit_price, 0, ',', '.') }} đ</td>
                            <td class="py-4 font-semibold">{{ number_format($item->subtotal, 0, ',', '.') }} đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 grid gap-3 text-sm text-slate-600 sm:w-1/2">
            <div class="flex justify-between">
                <span>Tạm tính</span>
                <span class="font-semibold">{{ number_format($order->subtotal, 0, ',', '.') }} đ</span>
            </div>
            <div class="flex justify-between">
                <span>Giảm giá</span>
                <span class="font-semibold">-{{ number_format($order->discount_total, 0, ',', '.') }} đ</span>
            </div>
            <div class="flex justify-between">
                <span>Phí vận chuyển</span>
                <span class="font-semibold">{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</span>
            </div>
            <div class="flex justify-between text-base font-semibold text-slate-900">
                <span>Tổng cộng</span>
                <span>{{ number_format($order->total, 0, ',', '.') }} đ</span>
            </div>
        </div>
    </section>
@endsection
