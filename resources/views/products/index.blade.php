@extends('layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-10">
        <div class="flex flex-col gap-4">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Catalog</p>
            <h1 class="text-4xl font-semibold">Khám phá sản phẩm</h1>
            <p class="text-slate-500">Tìm kiếm, lọc theo danh mục, thương hiệu và mức giá phù hợp.</p>
        </div>

        <form id="filter-form" data-filter-form action="{{ route('products.index') }}" class="grid gap-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm lg:grid-cols-4">
            <div class="lg:col-span-2">
                <label class="text-sm font-medium text-slate-600">Từ khóa</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Áo sơ mi, váy..." class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Danh mục</label>
                <select name="category" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">
                    <option value="">Tất cả</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Thương hiệu</label>
                <select name="brand" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">
                    <option value="">Tất cả</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(($filters['brand'] ?? '') == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="lg:col-span-2">
                <label class="text-sm font-medium text-slate-600">Giá thấp nhất</label>
                <input type="number" name="price_min" value="{{ $filters['price_min'] ?? '' }}" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900" placeholder="0">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Giá cao nhất</label>
                <input type="number" name="price_max" value="{{ $filters['price_max'] ?? '' }}" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900" placeholder="2.000.000">
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600">Sắp xếp</label>
                <select name="sort" class="mt-2 w-full rounded-2xl border-slate-200 focus:border-slate-900 focus:ring-slate-900">
                    <option value="latest" @selected(($filters['sort'] ?? '') === 'latest')>Mới nhất</option>
                    <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Giá tăng dần</option>
                    <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Giá giảm dần</option>
                </select>
            </div>
            <div class="lg:col-span-4 flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Áp dụng</button>
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600">Xóa lọc</a>
            </div>
        </form>

        <div data-products-grid>
            @include('products.partials.grid', ['products' => $products, 'showPagination' => true])
        </div>
    </div>
@endsection
