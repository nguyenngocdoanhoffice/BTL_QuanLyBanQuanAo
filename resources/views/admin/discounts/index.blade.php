@extends('layouts.admin')

@section('title', 'Mã giảm giá')
@section('header', 'Quản lý mã giảm giá')

@section('content')
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Mã giảm giá</h1>
                <p class="text-sm text-slate-500">Tạo và quản lý mã giảm giá áp dụng khi thanh toán.</p>
            </div>
            <a href="{{ route('admin.discounts.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white">
                Thêm mã giảm giá
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500">
                        <th class="py-3 font-medium">Mã</th>
                        <th class="py-3 font-medium">Loại</th>
                        <th class="py-3 font-medium">Giá trị</th>
                        <th class="py-3 font-medium">Đã dùng</th>
                        <th class="py-3 font-medium">Thời gian</th>
                        <th class="py-3 font-medium">Trạng thái</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($discounts as $discount)
                        <tr>
                            <td class="py-4 font-semibold">{{ $discount->code }}</td>
                            <td class="py-4">
                                {{ $discount->type === 'percentage' ? 'Phần trăm' : 'Cố định' }}
                            </td>
                            <td class="py-4 font-semibold">
                                @if ($discount->type === 'percentage')
                                    {{ rtrim(rtrim(number_format($discount->value, 2, '.', ','), '0'), '.') }}%
                                @else
                                    {{ number_format($discount->value, 0, ',', '.') }} đ
                                @endif
                            </td>
                            <td class="py-4">
                                {{ (int) $discount->used }}{{ $discount->usage_limit ? ' / ' . (int) $discount->usage_limit : '' }}
                            </td>
                            <td class="py-4 text-slate-600">
                                @php
                                    $start = $discount->starts_at?->format('d/m/Y H:i');
                                    $end = $discount->ends_at?->format('d/m/Y H:i');
                                @endphp
                                {{ $start || $end ? trim(($start ?? 'Từ nay') . ' → ' . ($end ?? 'Không giới hạn')) : 'Không giới hạn' }}
                            </td>
                            <td class="py-4">
                                @if ($discount->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Đang bật</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Tắt</span>
                                @endif
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="text-sm font-semibold text-slate-900">Sửa</a>
                                    <form method="POST" action="{{ route('admin.discounts.destroy', $discount) }}" onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-rose-600">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-500">Chưa có mã giảm giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $discounts->links() }}
        </div>
    </div>
@endsection
