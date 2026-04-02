@php($showPagination = $showPagination ?? false)

<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    @forelse ($products as $product)
        @include('products.partials.card', ['product' => $product])
    @empty
        <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-white p-10 text-center text-slate-500">
            Chưa có sản phẩm phù hợp.
        </div>
    @endforelse
</div>

@if ($showPagination)
    <div class="mt-8">
        {{ $products->links() }}
    </div>
@endif
