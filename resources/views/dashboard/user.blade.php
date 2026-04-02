@extends('layouts.app')

@section('title', 'My dashboard')

@section('content')
    <section class="max-w-6xl mx-auto px-4 py-16 space-y-8">
        <div class="flex flex-col gap-6 lg:flex-row">
            <div class="flex-1 rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
                <p class="text-sm uppercase tracking-wide text-slate-500">Signed in as</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight">{{ $user?->name }}</h1>
                <p class="text-slate-500">{{ $user?->email }}</p>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Orders placed</p>
                        <p class="mt-1 text-2xl font-semibold">{{ $ordersCount }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Lifetime spend</p>
                        <p class="mt-1 text-2xl font-semibold">{{ number_format($lifetimeSpend, 0, '.', ',') }} VND</p>
                    </div>
                </div>
                <div class="mt-8 inline-flex items-center gap-2 text-sm text-slate-500">
                    <span>Member since {{ optional($user?->created_at)->format('M d, Y') }}</span>
                    <span aria-hidden="true" class="text-slate-400">•</span>
                    <a href="{{ route('profile.edit') }}" class="font-medium text-slate-900">Update profile</a>
                </div>
            </div>
            <div class="w-full max-w-md rounded-3xl border border-slate-100 bg-slate-900 p-8 text-white shadow-sm">
                <p class="text-sm uppercase tracking-wide text-slate-300">Need support?</p>
                <h2 class="mt-2 text-2xl font-semibold">We are here for you</h2>
                <p class="mt-4 text-slate-200">Chat with a stylist, track a delivery, or request a return. Our support team answers within 24 hours.</p>
                <a href="mailto:support@qao-fashion.com" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur hover:bg-white/20">support@qao-fashion.com</a>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-wide text-slate-500">Recent orders</p>
                    <h2 class="text-2xl font-semibold tracking-tight">Your last five purchases</h2>
                </div>
                <a href="{{ route('profile.orders') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:border-slate-900">View all</a>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-500">
                            <th class="py-3 font-medium">Order</th>
                            <th class="py-3 font-medium">Status</th>
                            <th class="py-3 font-medium">Total</th>
                            <th class="py-3 font-medium">Placed</th>
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
                                <td class="py-4">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('profile.orders.show', $order) }}" class="text-sm font-medium text-slate-900">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500">No orders yet. Start shopping to see activity here.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
