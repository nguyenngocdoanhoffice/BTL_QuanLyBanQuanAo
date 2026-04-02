@extends('layouts.admin')

@section('title', 'Sửa banner')
@section('header', 'Cập nhật banner hero')

@section('content')
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold">Vui lòng kiểm tra:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold tracking-tight">Chỉnh sửa banner</h1>
        <p class="text-sm text-slate-500">Thay đổi hình ảnh hoặc trạng thái hiển thị.</p>

        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
            @method('PUT')
            @include('admin.banners._form')

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.banners.index') }}" class="rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600">Hủy</a>
                <button type="submit" class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Lưu thay đổi</button>
            </div>
        </form>
    </div>
@endsection
