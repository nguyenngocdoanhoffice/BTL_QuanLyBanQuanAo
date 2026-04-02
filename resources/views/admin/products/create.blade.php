@extends('layouts.admin')

@section('title', 'Sản phẩm mới')
@section('header', 'Thêm sản phẩm')

@section('content')
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold tracking-tight">Thêm sản phẩm mới</h1>
        <p class="mt-2 text-sm text-slate-500">Điền thông tin bên dưới để đăng sản phẩm.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <p class="font-semibold">Vui lòng kiểm tra:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-8">
            @csrf
            @include('admin.products._form', ['product' => null, 'stockQuantity' => $stockQuantity ?? 0])

            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.products.index') }}" class="rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 hover:border-slate-900">Hủy</a>
                <button type="submit" class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Lưu sản phẩm</button>
            </div>
        </form>
    </div>
@endsection
