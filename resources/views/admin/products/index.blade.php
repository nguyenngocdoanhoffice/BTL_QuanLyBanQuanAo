@extends('layouts.admin')

@section('title', 'Products')
@section('header', 'Product catalog')

@section('content')
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">All products</h1>
                <p class="text-sm text-slate-500">{{ $products->total() }} records</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">
                <span aria-hidden="true">＋</span>
                New product
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-slate-500">
                        <th class="py-3 font-medium">Product</th>
                        <th class="py-3 font-medium">Category</th>
                        <th class="py-3 font-medium">Price</th>
                        <th class="py-3 font-medium">Stock</th>
                        <th class="py-3 font-medium">Status</th>
                        <th class="py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse ($products as $product)
                        <tr>
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-2xl bg-slate-100">
                                        @if ($product->cover_image)
                                            <img src="{{ asset('storage/' . $product->cover_image) }}" alt="{{ $product->title }}" class="h-full w-full rounded-2xl object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $product->title }}</p>
                                        <p class="text-xs text-slate-500">SKU {{ $product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">{{ $product->category?->name ?? '—' }}</td>
                            <td class="py-4 font-semibold">{{ number_format($product->price, 0, '.', ',') }} VND</td>
                            <td class="py-4">{{ number_format((int) ($product->inventories_sum_quantity ?? 0)) }}</td>
                            <td class="py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">{{ $product->status }}</span>
                            </td>
                            <td class="py-4 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-semibold text-slate-900">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-rose-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-500">No products yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
@endsection
