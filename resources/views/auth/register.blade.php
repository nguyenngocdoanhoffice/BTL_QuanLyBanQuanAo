@extends('layouts.app')

@section('title', 'Create account')

@section('content')
    <section class="max-w-3xl mx-auto px-4 py-16">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <h1 class="text-3xl font-semibold tracking-tight">Join QAO Fashion</h1>
            <p class="mt-2 text-slate-500">Create an account to save favorites, track orders, and get member perks.</p>

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

            <form action="{{ route('register') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium text-slate-600">Full name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                </div>
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium text-slate-600">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                </div>
                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium text-slate-600">Password</label>
                    <input type="password" name="password" id="password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                    <p class="text-xs text-slate-500">At least 8 characters.</p>
                </div>
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-medium text-slate-600">Confirm password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 focus:border-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                </div>
                <button type="submit" class="w-full rounded-2xl bg-slate-900 px-6 py-3 text-white font-semibold">Create account</button>
                <p class="text-sm text-center text-slate-600">Already have an account? <a href="{{ route('login') }}" class="font-medium text-slate-900">Sign in</a></p>
            </form>
        </div>
    </section>
@endsection
