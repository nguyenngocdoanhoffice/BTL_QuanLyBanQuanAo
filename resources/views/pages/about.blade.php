@extends('layouts.app')

@section('title', 'Giới thiệu')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50 px-8 py-10">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">DOCHA Fashion</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Giới thiệu</h1>
                <p class="mt-3 max-w-2xl text-slate-600">
                    Thời trang nam nữ hiện đại, dễ mặc mỗi ngày. Tập trung vào chất liệu tốt, form gọn gàng và trải nghiệm mua sắm đơn giản.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Khám phá sản phẩm</a>
                    <a href="{{ route('pages.contact') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700">Liên hệ</a>
                </div>
            </div>

            <div class="px-8 py-10">
                <div class="grid gap-6 md:grid-cols-3">
                    <div class="rounded-3xl border border-slate-100 bg-white p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Sản phẩm</p>
                        <p class="mt-2 text-lg font-semibold">Cập nhật theo mùa</p>
                        <p class="mt-2 text-sm text-slate-600">Ưu tiên màu sắc dễ phối và chất liệu thoải mái.</p>
                    </div>
                    <div class="rounded-3xl border border-slate-100 bg-white p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Giao hàng</p>
                        <p class="mt-2 text-lg font-semibold">Nhanh, đóng gói kỹ</p>
                        <p class="mt-2 text-sm text-slate-600">Kiểm tra hàng cẩn thận trước khi gửi đi.</p>
                    </div>
                    <div class="rounded-3xl border border-slate-100 bg-white p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Hỗ trợ</p>
                        <p class="mt-2 text-lg font-semibold">Tư vấn chọn size</p>
                        <p class="mt-2 text-sm text-slate-600">Gợi ý size theo chiều cao/cân nặng nhanh chóng.</p>
                    </div>
                </div>

                <div class="mt-10 grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
                    <div class="rounded-3xl bg-slate-50 p-6">
                        <h2 class="text-lg font-semibold">Vì sao chọn DOCHA?</h2>
                        <ul class="mt-4 space-y-2 text-sm text-slate-600">
                            <li>Form basic dễ mặc, dễ phối nhiều hoàn cảnh.</li>
                            <li>Giá rõ ràng, ưu đãi cập nhật theo từng bộ sưu tập.</li>
                            <li>Kho size minh bạch theo từng sản phẩm.</li>
                        </ul>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Đi nhanh</p>
                        <div class="mt-4 space-y-3">
                            <a href="{{ route('products.index') }}" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-100">Xem tất cả sản phẩm →</a>
                            <a href="{{ route('pages.contact') }}" class="block rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 hover:bg-slate-100">Hỗ trợ & liên hệ →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
