@extends('layouts.admin')

@section('title', 'Banners')
@section('header', 'Quản lý banner hero')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Banner hero</h1>
                <p class="text-sm text-slate-500">Cập nhật hình ảnh hiển thị ở trang chủ.</p>
            </div>
            <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">+ Thêm banner</a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-slate-500">
                    <tr>
                        <th class="py-3 font-medium">Ảnh</th>
                        <th class="py-3 font-medium">Tiêu đề</th>
                        <th class="py-3 font-medium">Liên kết</th>
                        <th class="py-3 font-medium">Trạng thái</th>
                        <th class="py-3 font-medium">Thứ tự</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($banners as $banner)
                        <tr>
                            <td class="py-4">
                                <div class="h-16 w-28 overflow-hidden rounded-2xl bg-slate-100">
                                    @if ($banner->image_path)
                                        <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" class="h-full w-full object-cover">
                                    @endif
                                </div>
                            </td>
                            <td class="py-4">
                                <p class="font-semibold">{{ $banner->title }}</p>
                                <p class="text-xs text-slate-500">{{ $banner->subtitle }}</p>
                            </td>
                            <td class="py-4 text-slate-500">{{ $banner->link_url ?: '—' }}</td>
                            <td class="py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $banner->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $banner->is_active ? 'Đang hiển thị' : 'Tạm ẩn' }}
                                </span>
                            </td>
                            <td class="py-4 font-semibold">{{ $banner->sort_order }}</td>
                            <td class="py-4 text-right">
                                <div class="flex justify-end gap-3 text-sm font-semibold">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="text-slate-900">Sửa</a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Xóa banner này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">Chưa có banner nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
