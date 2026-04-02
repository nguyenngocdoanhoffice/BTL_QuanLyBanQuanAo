@extends('layouts.app')

@section('title', 'Sign in')

@section('content')
    <section class="max-w-3xl mx-auto px-4 py-16">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <h1 class="text-3xl font-semibold tracking-tight">Welcome back</h1>
            <p class="mt-2 text-slate-500">Sign in to manage orders and track your wardrobe.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <p class="font-semibold">Please check the following:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-slate-600">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                </div>
                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium text-slate-600">Password</label>
                    <input type="password" name="password" id="password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="inline-flex items-center gap-2 text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                        Remember me
                    </label>
                    <a href="{{ route('register') }}" class="font-medium text-slate-900">Need an account?</a>
                </div>
                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-6 py-3 text-white font-semibold">Sign in</button>
            </form>
        </div>
    </section>
@endsection
