@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50 px-8 py-10">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">Hỗ trợ</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Liên hệ</h1>
                <p class="mt-3 max-w-2xl text-slate-600">Bạn cần tư vấn size hoặc tình trạng đơn hàng? Liên hệ để được hỗ trợ nhanh.</p>
            </div>

            <div class="px-8 py-10">
                <div class="grid gap-6 lg:grid-cols-[1fr,1fr]">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6">
                        <h2 class="text-lg font-semibold">Thông tin liên hệ</h2>
                        <dl class="mt-5 space-y-4 text-sm">
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Hotline</dt>
                                <dd class="font-semibold text-slate-800">1900 1234</dd>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Email</dt>
                                <dd class="font-semibold text-slate-800">support@docha-fashion.com</dd>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Giờ làm việc</dt>
                                <dd class="font-semibold text-slate-800">08:00 – 21:00 (T2 – CN)</dd>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Khu vực</dt>
                                <dd class="font-semibold text-slate-800">Việt Nam</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-3xl bg-slate-50 p-6">
                        <h2 class="text-lg font-semibold">Gợi ý nhanh</h2>
                        <div class="mt-5 space-y-3 text-sm text-slate-600">
                            <div class="rounded-2xl bg-white px-4 py-3">
                                <p class="font-semibold text-slate-800">Tư vấn size</p>
                                <p class="mt-1">Gửi chiều cao + cân nặng + size bạn hay mặc.</p>
                            </div>
                            <div class="rounded-2xl bg-white px-4 py-3">
                                <p class="font-semibold text-slate-800">Hỗ trợ đơn hàng</p>
                                <p class="mt-1">Cung cấp mã đơn để kiểm tra trạng thái nhanh.</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Khám phá sản phẩm</a>
                            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700">Về trang chủ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
