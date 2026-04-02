@extends('layouts.app')

@section('title', 'Tài khoản của tôi')

@section('content')
    <section class="max-w-6xl mx-auto px-4 py-16 space-y-8">
        <div class="flex flex-col gap-6 lg:flex-row">
            <div class="flex-1 rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
                <p class="text-sm uppercase tracking-wide text-slate-500">Đăng nhập với</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight">{{ $user?->name }}</h1>
                <p class="text-slate-500">{{ $user?->email }}</p>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Đơn đã đặt</p>
                        <p class="mt-1 text-2xl font-semibold">{{ $ordersCount }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Tổng chi tiêu</p>
                        <p class="mt-1 text-2xl font-semibold">{{ number_format($lifetimeSpend, 0, '.', ',') }} VND</p>
                    </div>
                </div>
                <div class="mt-8 inline-flex items-center gap-2 text-sm text-slate-500">
                    <span>Thành viên từ {{ optional($user?->created_at)->format('d/m/Y') }}</span>
                    <span aria-hidden="true" class="text-slate-400">•</span>
                    <a href="{{ route('profile.edit') }}" class="font-medium text-slate-900">Cập nhật hồ sơ</a>
                </div>
            </div>
            <div class="w-full max-w-md rounded-3xl border border-slate-100 bg-slate-900 p-8 text-white shadow-sm">
                <p class="text-sm uppercase tracking-wide text-slate-300">Cần hỗ trợ?</p>
                <h2 class="mt-2 text-2xl font-semibold">Chúng tôi luôn sẵn sàng</h2>
                <p class="mt-4 text-slate-200">Liên hệ để được tư vấn, theo dõi giao hàng hoặc yêu cầu đổi/trả. Đội ngũ hỗ trợ phản hồi trong 24 giờ.</p>
                <a href="mailto:support@docha-fashion.com" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur hover:bg-white/20">support@docha-fashion.com</a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-wide text-slate-500">Đơn hàng gần đây</p>
                    <h2 class="text-2xl font-semibold tracking-tight">5 đơn mua gần nhất</h2>
                </div>
                <a href="{{ route('profile.orders') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:border-slate-900">Xem tất cả</a>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-500">
                            <th class="py-3 font-medium">Mã đơn</th>
                            <th class="py-3 font-medium">Trạng thái</th>
                            <th class="py-3 font-medium">Tổng</th>
                            <th class="py-3 font-medium">Ngày đặt</th>
                            <th class="py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="py-4 font-semibold">{{ $order->code }}</td>
                                <td class="py-4">
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold tracking-wide text-slate-600">{{ $order->status_label }}</span>
                                </td>
                                <td class="py-4 font-semibold">{{ number_format($order->total, 0, '.', ',') }} VND</td>
                                <td class="py-4">{{ $order->created_at->format('d/m/Y') }}</td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('profile.orders.show', $order) }}" class="text-sm font-medium text-slate-900">Xem</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500">Chưa có đơn hàng. Hãy mua sắm để xem lịch sử tại đây.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
